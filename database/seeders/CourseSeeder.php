<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Course 1: Introducción al Trading (Free Course)
        $course1 = Course::create([
            'title' => 'Introducción al Trading (101)',
            'slug' => Str::slug('Introducción al Trading (101)'),
            'description' => 'Aprende los conceptos fundamentales del mercado financiero, desde cero hasta tus primeras operaciones. Ideal para principiantes.',
            'image_url' => 'https://images.unsplash.com/photo-1543286386-713bdd548da4?q=80&w=2070&auto=format&fit=crop',
            'is_premium' => false,
            'status' => 'published',
        ]);

        $section1_1 = $course1->sections()->create([
            'title' => 'Fundamentos del Mercado',
            'order' => 1,
        ]);

        $section1_1->lessons()->create([
            'title' => '¿Qué es el Trading?',
            'slug' => Str::slug('Que es el Trading'),
            'video_path' => 'public/lms/videos/sample_video_1.mp4',
            'content' => 'Una introducción al mundo del trading y los mercados financieros. Exploraremos qué es el trading, por qué la gente opera y los fundamentos básicos que necesitas conocer.',
            'order' => 1,
        ]);
        $section1_1->lessons()->create([
            'title' => 'Tipos de Mercados Financieros',
            'slug' => Str::slug('Tipos de Mercados Financieros'),
            'video_path' => 'public/lms/videos/sample_video_2.mp4',
            'content' => 'Descubre los diferentes mercados donde puedes operar, como acciones, divisas (Forex), materias primas e índices, y sus características principales.',
            'order' => 2,
        ]);

        $section1_2 = $course1->sections()->create([
            'title' => 'Análisis Básico',
            'order' => 2,
        ]);

        $section1_2->lessons()->create([
            'title' => 'Gráficos y Velas Japonesas',
            'slug' => Str::slug('Graficos y Velas Japonesas'),
            'video_path' => 'public/lms/videos/sample_video_3.mp4',
            'content' => 'Aprende a leer e interpretar los gráficos de precios y las velas japonesas, herramientas esenciales para todo trader.',
            'order' => 1,
        ]);

        // Course 2: Estrategias de Opciones Avanzadas (Premium Course)
        $course2 = Course::create([
            'title' => 'Estrategias de Opciones Avanzadas',
            'slug' => Str::slug('Estrategias de Opciones Avanzadas'),
            'description' => 'Domina las estrategias más sofisticadas de opciones con acceso exclusivo a casos reales y análisis detallados. Diseñado para traders con experiencia.',
            'image_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?q=80&w=2070&auto=format&fit=crop',
            'is_premium' => true,
            'status' => 'published',
        ]);

        $section2_1 = $course2->sections()->create([
            'title' => 'Estrategias con Opciones',
            'order' => 1,
        ]);

        $section2_1->lessons()->create([
            'title' => 'Spreads Verticales',
            'slug' => Str::slug('Spreads Verticales'),
            'video_path' => 'public/lms/videos/sample_video_4.mp4',
            'content' => 'Explora cómo construir y gestionar spreads verticales de compra y venta para optimizar tu riesgo-recompensa.',
            'order' => 1,
        ]);
        $section2_1->lessons()->create([
            'title' => 'Straddles y Strangles',
            'slug' => Str::slug('Straddles y Strangles'),
            'video_path' => 'public/lms/videos/sample_video_5.mp4',
            'content' => 'Aprende a operar la volatilidad del mercado usando estrategias de straddles y strangles en diferentes escenarios.',
            'order' => 2,
        ]);

        // Course 3: Análisis Técnico Avanzado (Free Course)
        $course3 = Course::create([
            'title' => 'Análisis Técnico Avanzado',
            'slug' => Str::slug('Análisis Técnico Avanzado'),
            'description' => 'Domina los indicadores y patrones de gráficos más complejos para tomar decisiones de trading informadas. Curso ideal para quienes ya tienen nociones básicas.',
            'image_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?q=80&w=2070&auto=format&fit=crop',
            'is_premium' => false,
            'status' => 'published',
        ]);

        $section3_1 = $course3->sections()->create([
            'title' => 'Indicadores Avanzados',
            'order' => 1,
        ]);

        $section3_1->lessons()->create([
            'title' => 'El Oscilador Estocástico',
            'slug' => Str::slug('El Oscilador Estocastico'),
            'video_path' => 'public/lms/videos/sample_video_6.mp4',
            'content' => 'Profundiza en el uso del oscilador estocástico para identificar condiciones de sobrecompra y sobreventa.',
            'order' => 1,
        ]);
    }
}
