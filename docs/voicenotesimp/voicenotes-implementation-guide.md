# Guide: Implementing Voice Notes in Chat

This document provides a step-by-step guide to integrate a "hold-to-record" voice message feature into the existing chat, based on the concepts in `voicenotes.txt` and tailored to your files `app/Livewire/UserPanel/Chat.php` and `resources/views/user_panel/chat.blade.php`.

---

## Step 1: Backend Setup

### A. Create a `VoiceNote` Model

First, create the model file for our `voice_notes` table.

**Action:** Run this command in your terminal:
```bash
php artisan make:model VoiceNote
```

**Action:** Open the newly created `app/Models/VoiceNote.php` and add the following content:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VoiceNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_channel_id',
        'user_id',
        'file_path',
        'duration',
    ];

    protected $appends = ['url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chatChannel()
    {
        return $this->belongsTo(ChatChannel::class);
    }

    public function chatMessage()
    {
        return $this->hasOne(ChatMessage::class);
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
```

### B. Create a New Migration

We need to add a foreign key to our `chat_messages` table so a message can be associated with a voice note.

**Action:** Run this command in your terminal:
```bash
php artisan make:migration add_voice_note_id_to_chat_messages_table --table=chat_messages
```

**Action:** Open the generated migration file and update the `up()` and `down()` methods:

```php
// ...
public function up(): void
{
    Schema::table('chat_messages', function (Blueprint $table) {
        $table->foreignId('voice_note_id')->nullable()->constrained('voice_notes')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('chat_messages', function (Blueprint $table) {
        $table->dropForeign(['voice_note_id']);
        $table->dropColumn('voice_note_id');
    });
}
// ...
```
> **Note:** We use `onDelete('cascade')` here so if a voice note is deleted, the corresponding chat message is also removed.

### C. Update `ChatMessage` Model

**Action:** Open `app/Models/ChatMessage.php` and add the `voiceNote` relationship. Also, make the `message` field fillable as it might be empty for voice notes.

```php
// ... existing class
class ChatMessage extends Model
{
    // ...
    protected $fillable = [
        'chat_channel_id',
        'user_id',
        'message',
        'parent_message_id',
        'voice_note_id', // Add this
    ];
    // ...

    public function voiceNote()
    {
        return $this->belongsTo(VoiceNote::class);
    }

    // ...
}
```

### D. Update `Chat.php` Livewire Component

Now, let's add the logic to handle the file upload.

**Action:** Open `app/Livewire/UserPanel/Chat.php` and apply the following changes:

```php
<?php

namespace App\Livewire\UserPanel;

use App\Events\NewChatMessage;
// ...
use App\Models\VoiceNote; // Add this
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads; // Add this

#[Layout('layouts.app')]
class Chat extends Component
{
    use WithFileUploads; // Add this trait

    // ... existing properties

    public $tempVoiceNoteFile; // Add this property

    // ... existing methods (mount, loadChannels, etc.)

    public function sendMessage(): void
    {
        // ... this method remains the same
    }
    
    // Add the new method below sendMessage()
    public function sendVoiceMessage()
    {
        $this->validate([
            'tempVoiceNoteFile' => 'required|file|mimes:mp3,wav,ogg,webm|max:10240', // 10MB Max
        ]);

        if (!$this->activeChannel) {
            return;
        }

        // 1. Store the file
        $path = $this->tempVoiceNoteFile->store('voicenotes', 'public');

        // 2. Create the VoiceNote record
        $voiceNote = VoiceNote::create([
            'chat_channel_id' => $this->activeChannel->id,
            'user_id'         => Auth::id(),
            'file_path'       => $path,
            'duration'        => 0, // We can calculate this on the frontend later if needed
        ]);

        // 3. Create the ChatMessage record, linking the voice note
        $message = ChatMessage::create([
            'chat_channel_id'   => $this->activeChannel->id,
            'user_id'           => Auth::id(),
            'message'           => '', // Voice notes have no text content
            'voice_note_id'     => $voiceNote->id,
        ]);

        $message->load(['user.profile', 'voiceNote']);

        // 4. Broadcast the new message event
        try {
            broadcast(new NewChatMessage($message));
            Log::info('✅ Voice message broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('❌ Voice message broadcast failed', ['error' => $e->getMessage(), 'message_id' => $message->id]);
        }

        // 5. Update channel's last message timestamp
        $this->activeChannel->update(['last_message_at' => now()]);

        // 6. Add to local collection to show immediately
        $this->chatMessages->push($message);

        // 7. Reset state and scroll
        $this->reset('tempVoiceNoteFile');
        $this->dispatch('scroll-chat-to-bottom');
    }
    
    // ... rest of the component
}
```

### E. Run the Migration

**Action:** Run the new migration in your terminal.
```bash
php artisan migrate
```

---

## Step 2: Frontend Implementation (`chat.blade.php`)

### A. Update the Message Input UI

We will change the send button to a microphone button when the input is empty.

**Action:** Open `resources/views/user_panel/chat.blade.php`. Locate the message input `<form>` at the bottom and replace it with this new version:

```html
{{-- Message Input --}}
<div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
    {{-- ... existing replyingTo block ... --}}

    <form wire:submit.prevent="sendMessage" @submit.prevent="if($wire.messageText.trim() !== '') { $wire.sendMessage() }" class="flex items-end gap-3">
        <div class="relative flex-grow">
            {{-- ... existing textarea ... --}}
        </div>

        {{-- Dynamic Button Container --}}
        <div class="flex-shrink-0" wire:loading.remove wire:target="tempVoiceNoteFile">
            {{-- Send Button (shows when typing) --}}
            <button type="submit" x-show="$wire.messageText.trim() !== ''" x-transition class="p-3 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-colors">
                 <x-ionicon-rocket-outline class="w-6 h-6"/>
            </button>

            {{-- Record Button (shows when input is empty) --}}
            <button type="button" 
                    x-show="$wire.messageText.trim() === ''"
                    x-transition
                    @mousedown.prevent="startRecording()"
                    @mouseup.prevent="stopRecording()"
                    @touchstart.passive.prevent="startRecording()"
                    @touchend.passive.prevent="stopRecording()"
                    :class="{ 'bg-red-500 text-white animate-pulse': isRecording }"
                    class="p-3 rounded-full bg-green-500 text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <x-ionicon-mic-outline class="w-6 h-6"/>
            </button>
        </div>
        
        {{-- Loading indicator for voice note upload --}}
        <div wire:loading.flex wire:target="tempVoiceNoteFile" class="flex-shrink-0 p-3 rounded-full bg-yellow-500 text-white">
            <x-ionicon-sync class="w-6 h-6 animate-spin"/>
        </div>
    </form>
</div>
```

### B. Extend the Alpine.js Component

We need to add the recording logic to `chatComponent()`.

**Action:** In `resources/views/user_panel/chat.blade.php`, find the `@push('scripts')` section and replace the `chatComponent()` function with this updated version:

```javascript
<script>
    function chatComponent() {
        return {
            wire: null,
            activeChannelId: null,
            isRecording: false,
            mediaRecorder: null,
            audioChunks: [],
            
            init(wire, activeChannelId) {
                // ... same as before
            },
            
            initEcho() {
                // ... same as before
            },

            leaveEchoChannel() {
                // ... same as before
            },

            setupScroll() {
                // ... same as before
            },
            
            scrollToBottom() {
                // ... same as before
            },

            startRecording() {
                if (this.isRecording) return;
                
                navigator.mediaDevices.getUserMedia({ audio: true })
                    .then(stream => {
                        this.isRecording = true;
                        this.audioChunks = [];
                        
                        // Use webm format as it's widely supported
                        this.mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });
                        this.mediaRecorder.start();

                        this.mediaRecorder.addEventListener("dataavailable", event => {
                            this.audioChunks.push(event.data);
                        });
                        
                        // Stop the stream tracks when recording stops
                        this.mediaRecorder.onstop = () => {
                            stream.getTracks().forEach(track => track.stop());
                        };

                        console.log('Recording started...');
                    })
                    .catch(err => {
                        console.error("Error accessing microphone:", err);
                        alert('No se pudo acceder al micrófono. Asegúrate de dar permiso en tu navegador.');
                    });
            },

            stopRecording() {
                if (!this.isRecording) return;
                
                this.mediaRecorder.stop();
                this.isRecording = false; // UI feedback immediately

                this.mediaRecorder.addEventListener("stop", () => {
                    const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                    // Use a unique name to avoid conflicts, though Livewire handles this
                    const audioFile = new File([audioBlob], `voice-note-${Date.now()}.webm`, { type: "audio/webm" });

                    console.log('Recording stopped, uploading...');
                    
                    @this.upload('tempVoiceNoteFile', audioFile, 
                        (uploadedFilename) => {
                            console.log('Upload successful. Sending message.');
                            // On success, call the Livewire method to create the message
                            @this.sendVoiceMessage();
                        }, 
                        (error) => {
                            console.error('Upload failed.', error);
                            alert('La subida de la nota de voz falló. Inténtalo de nuevo.');
                        }
                    );
                });
            },
        }
    }
</script>
```

### C. Display Voice Notes in the Chat

Finally, update the message loop to show an audio player if the message is a voice note.

**Action:** In `resources/views/user_panel/chat.blade.php`, find the message bubble display logic (`@forelse($chatMessages as $message)`) and modify it:

```html
{{-- Message Bubble --}}
<div class="relative bg-white dark:bg-gray-700 p-3 rounded-lg shadow-sm @if($message->user_id == auth()->id()) rounded-tr-none @else rounded-tl-none @endif @if($message->voiceNote) w-72 @else max-w-xl @endif">
    @if ($message->voiceNote)
        <audio controls src="{{ $message->voiceNote->url }}" class="w-full"></audio>
    @else
        <p class="text-base text-gray-800 dark:text-gray-200" style="white-space: pre-wrap;">{!! nl2br(e($message->message)) !!}</p>
    @endif
</div>
```
> **Note:** We've added a fixed width (`w-72`) for voice notes for a more consistent UI, but you can adjust this.

---

You have now implemented the voice notes feature. Clear your browser cache and test it out. 
