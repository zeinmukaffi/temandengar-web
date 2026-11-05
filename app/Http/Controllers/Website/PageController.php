<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('public.pages.about');
    }

    public function services(): View
    {
        $services = [
            [
                'icon' => 'bi-mortarboard',
                'title' => 'Akademik dan Perkuliahan',
                'description' => 'Ruang berbagi pengalaman mengenai tugas, adaptasi perkuliahan, manajemen waktu, dan aktivitas belajar.',
            ],
            [
                'icon' => 'bi-heart',
                'title' => 'Pengembangan Diri',
                'description' => 'Diskusi tentang motivasi, kepercayaan diri, dan pengembangan potensi diri.',
            ],
            [
                'icon' => 'bi-people',
                'title' => 'Pertemanan dan Sosial',
                'description' => 'Berbagi pengalaman mengenai dinamika pertemanan dan relasi sosial.',
            ],
            [
                'icon' => 'bi-diagram-3',
                'title' => 'Organisasi dan Kampus',
                'description' => 'Diskusi pengalaman dalam kegiatan organisasi, kepemimpinan, dan kehidupan kampus.',
            ],
        ];

        return view('public.pages.services', compact('services'));
    }

    public function faq(): View
    {
        $faqs = [
            [
                'question' => 'Apa itu TemanDengar?',
                'answer' => 'TemanDengar merupakan platform yang menghubungkan mahasiswa dengan konselor sebaya untuk berbagi cerita dan mendapatkan dukungan melalui percakapan yang suportif.',
            ],
            [
                'question' => 'Apa yang dimaksud dengan konselor sebaya?',
                'answer' => 'Konselor sebaya adalah teman yang memiliki peran mendengarkan dan memberikan dukungan berdasarkan pelatihan serta batasan perannya. Konselor sebaya bukan pengganti psikolog atau tenaga profesional.',
            ],
            [
                'question' => 'Bagaimana cara mengajukan konsultasi?',
                'answer' => 'Kamu dapat mengisi formulir konsultasi pada halaman TemanDengar. Setelah permintaan diterima, konselor akan meninjau dan menghubungimu untuk membahas langkah selanjutnya.',
            ],
            [
                'question' => 'Apakah konsultasi dilakukan secara online?',
                'answer' => 'Konsultasi dapat direncanakan secara online maupun tatap muka. Metode dan jadwal akan disepakati bersama konselor.',
            ],
            [
                'question' => 'Apakah saya perlu membuat akun?',
                'answer' => 'Tidak. Pada versi TemanDengar ini, mahasiswa cukup mengisi formulir konsultasi. Akun login disediakan khusus bagi konselor.',
            ],
            [
                'question' => 'Apakah identitas dan cerita saya dijaga?',
                'answer' => 'Informasi pengajuan ditujukan untuk pengelolaan konsultasi dan hanya boleh diakses oleh pihak yang berwenang. Hindari menuliskan informasi yang tidak diperlukan. Baca kebijakan privasi untuk memahami penggunaan data.',
            ],
            [
                'question' => 'Apakah TemanDengar memberikan diagnosis?',
                'answer' => 'Tidak. TemanDengar merupakan layanan dukungan teman sebaya, bukan layanan diagnosis atau terapi profesional.',
            ],
            [
                'question' => 'Bagaimana jika saya membutuhkan bantuan profesional?',
                'answer' => 'Konselor dapat mengarahkanmu ke layanan profesional atau layanan dukungan kampus yang sesuai. Dalam kondisi darurat, segera hubungi layanan darurat atau tenaga profesional di wilayahmu.',
            ],
        ];

        return view('public.pages.faq', compact('faqs'));
    }

    public function privacy(): View
    {
        return view('public.pages.privacy');
    }
}