<?php

namespace Database\Seeders;

use App\Models\AboutContent;
use App\Models\Church;
use App\Models\ChurchService;
use App\Models\ContactPerson;
use App\Models\DailyVerse;
use App\Models\Event;
use App\Models\Pastor;
use App\Models\Sermon;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Church Profile
        $church = Church::create([
            'name' => 'AOCC',
            'short_name' => 'AOCC',
            'full_name' => 'Alfa Omega Community Church',
            'tagline' => 'Welcome Home',
            'logo_path' => '/images/aocc-logo.svg',
            'address' => 'Gedung Grha Alfa Omega, Jl. Boulevard Barat Kav. 88, Jakarta Utara',
            'phone' => '+62 21 4587 9000',
            'email' => 'info@aocc.church',
            'instagram' => 'https://instagram.com/aoccchurch',
            'youtube' => 'https://youtube.com/@aoccchurch',
            'maps_url' => 'https://maps.google.com/?q=AOCC+Church',
        ]);

        // 2. About & BETHEL Philosophy
        AboutContent::create([
            'church_id' => $church->id,
            'vision' => 'Menjadi gereja yang memulihkan, memperlengkapi, dan mengutus setiap jiwa untuk memancarkan kasih Kristus di tengah generasi ini.',
            'mission' => "1. Menghadirkan atmosfer hadirat Tuhan yang nyata melalui doa, pujian, dan penyembahan yang intim.\n2. Memuridkan jemaat dari segala usia agar berakar kuat pada kebenaran Firman Allah dan berbuah lebat.\n3. Membangun keluarga dan komunitas yang kokoh, saling menopang dalam kasih karunia.\n4. Menjadi agen transformasi sosial yang membawa dampak nyata dan kepedulian bagi kota dan bangsa.",
            'philosophy_title' => 'Filosofi Pelayanan BETHEL',
            'philosophy_body' => 'Setiap nafas pelayanan di Alfa Omega Community Church berakar teguh pada 6 pilar BETHEL, yang menjadi jati diri dan kompas kami dalam melayani Tuhan dan sesama.',
            'bethel_json' => [
                [
                    'letter' => 'B',
                    'title' => 'Biblical Foundation',
                    'tagline' => 'Berakar pada Firman',
                    'description' => 'Alkitab adalah otoritas tertinggi, kompas hidup, dan dasar tak tergoyahkan bagi setiap pengajaran, keputusan, dan langkah pelayanan kami.',
                ],
                [
                    'letter' => 'E',
                    'title' => 'Excellence in Service',
                    'tagline' => 'Memberi yang Terbaik',
                    'description' => 'Kami percaya bahwa keunggulan (excellence) adalah bentuk penyembahan yang tulus. Kami melayani Tuhan bukan dengan sisa tenaga, melainkan dedikasi tertinggi.',
                ],
                [
                    'letter' => 'T',
                    'title' => 'Transformational Community',
                    'tagline' => 'Komunitas yang Memulihkan',
                    'description' => 'Gereja adalah rumah bagi setiap orang yang lelah dan terluka. Di sini ada pemulihan, penerimaan tanpa syarat, dan pertumbuhan rohani yang sejati.',
                ],
                [
                    'letter' => 'H',
                    'title' => 'Heart of Worship',
                    'tagline' => 'Gaya Hidup Menyembah',
                    'description' => 'Penyembahan bukan sekadar daftar lagu di hari Minggu, melainkan detak jantung dan gaya hidup ketaatan penuh rasa syukur di hadapan Allah setiap hari.',
                ],
                [
                    'letter' => 'E',
                    'title' => 'Empowerment & Discipleship',
                    'tagline' => 'Memperlengkapi Generasi',
                    'description' => 'Kami berkomitmen untuk menemukan potensi rohani, mendampingi, dan mempercayakan tongkat estafet kepemimpinan kepada generasi muda.',
                ],
                [
                    'letter' => 'L',
                    'title' => 'Love in Action',
                    'tagline' => 'Kasih yang Nyata',
                    'description' => 'Kasih Kristus terwujud dalam kepedulian sosial, uluran tangan bagi yang membutuhkan, dan kerelaan berkorban demi keselamatan sesama.',
                ],
            ],
        ]);

        // 3. Contact Persons (Primary: Pakdhe Ardi)
        $pakdheArdi = ContactPerson::create([
            'name' => 'Pakdhe Ardi',
            'role' => 'Pastoral Care & Layanan Jemaat',
            'whatsapp_number' => '6281288997766',
            'default_message' => 'Shalom Pakdhe Ardi, saya ingin bertanya seputar jadwal ibadah dan terhubung dengan keluarga besar AOCC.',
        ]);

        $cpYouth = ContactPerson::create([
            'name' => 'Kak Daniel Kurniawan',
            'role' => 'Youth & Teens Coordinator',
            'whatsapp_number' => '6281311223344',
            'default_message' => 'Shalom Kak Daniel, saya ingin join komunitas AOCC Youth & Teens!',
        ]);

        $cpKids = ContactPerson::create([
            'name' => 'Ibu Ruth Marlina',
            'role' => 'Kids Church Director',
            'whatsapp_number' => '6281599887766',
            'default_message' => 'Shalom Ibu Ruth, saya ingin mendaftarkan anak saya di AOCC Kids Church.',
        ]);

        $cpFamily = ContactPerson::create([
            'name' => 'Bpk. Markus & Ibu Sandra',
            'role' => 'Family Life & Community Leader',
            'whatsapp_number' => '6281677889900',
            'default_message' => 'Shalom, kami rindu bergabung di Beranda Komunitas Keluarga AOCC.',
        ]);

        // 4. Pastors Profile
        $seniorPastor = Pastor::create([
            'name' => 'Pdt. Jonathan Abraham, M.Th.',
            'title' => 'Senior Pastor',
            'photo_path' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80',
            'bio' => 'Melayani dengan hati kebapaan dan kerinduan mendalam melihat setiap jiwa mengalami kasih Bapa yang sejati. Bersama istrinya, Ibu Maria Abraham, beliau memimpin AOCC sejak 2012 dengan komitmen memuridkan generasi dan memberkati bangsa.',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        Pastor::create([
            'name' => 'Pdt. Samuel Santoso',
            'title' => 'Associate Pastor',
            'photo_path' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=800&q=80',
            'bio' => 'Fokus pada pemuridan, pembinaan pasutri muda, dan penguatan doktrin Alkitabiah di tengah tantangan zaman modern.',
            'is_primary' => false,
            'sort_order' => 2,
        ]);

        // 5. Daily Verses (Default is Kejadian 1:1)
        DailyVerse::create([
            'verse_date' => now()->toDateString(),
            'book' => 'Kejadian',
            'chapter' => 1,
            'verse_from' => 1,
            'verse_to' => null,
            'text' => 'Pada mulanya Allah menciptakan langit dan bumi.',
            'translation' => 'TB',
            'is_default' => true,
        ]);

        DailyVerse::create([
            'verse_date' => now()->addDay()->toDateString(),
            'book' => 'Mazmur',
            'chapter' => 23,
            'verse_from' => 1,
            'verse_to' => 3,
            'text' => 'TUHAN adalah gembalaku, takkan kekurangan aku. Ia membaringkan aku di padang yang berumput hijau, Ia membimbing aku ke air yang tenang; Ia menyegarkan jiwaku.',
            'translation' => 'TB',
            'is_default' => false,
        ]);

        DailyVerse::create([
            'verse_date' => now()->addDays(2)->toDateString(),
            'book' => 'Yohanes',
            'chapter' => 15,
            'verse_from' => 5,
            'verse_to' => null,
            'text' => 'Akulah pokok anggur dan kamulah ranting-rantingnya. Barangsiapa tinggal di dalam Aku dan Aku di dalam dia, ia berbuah banyak, sebab di luar Aku kamu tidak dapat berbuat apa-apa.',
            'translation' => 'TB',
            'is_default' => false,
        ]);

        // 6. Service Categories (Group 'ibadah' - 6 Jenis & Group 'beranda' - 3 Jenis)
        // Ibadah 6 jenis: Ibadah Raya, Youth, Teens, Kids, Kaum Pria, Kaum Wanita
        $catRaya = ServiceCategory::create(['group' => 'ibadah', 'slug' => 'ibadah-raya', 'name' => 'Ibadah Raya', 'sort_order' => 1]);
        $catYouth = ServiceCategory::create(['group' => 'ibadah', 'slug' => 'youth-service', 'name' => 'Youth Service', 'sort_order' => 2]);
        $catTeens = ServiceCategory::create(['group' => 'ibadah', 'slug' => 'teens-service', 'name' => 'Teens Service', 'sort_order' => 3]);
        $catKids = ServiceCategory::create(['group' => 'ibadah', 'slug' => 'kids-church', 'name' => 'Kids Church', 'sort_order' => 4]);
        $catPria = ServiceCategory::create(['group' => 'ibadah', 'slug' => 'kaum-pria', 'name' => 'Kaum Pria', 'sort_order' => 5]);
        $catWanita = ServiceCategory::create(['group' => 'ibadah', 'slug' => 'kaum-wanita', 'name' => 'Kaum Wanita', 'sort_order' => 6]);

        // Beranda 3 jenis: Youth, Pro-M, Keluarga
        $catBerandaYouth = ServiceCategory::create(['group' => 'beranda', 'slug' => 'beranda-youth', 'name' => 'Youth Community', 'sort_order' => 1]);
        $catBerandaProM = ServiceCategory::create(['group' => 'beranda', 'slug' => 'beranda-pro-m', 'name' => 'Pro-M (Professional Ministry)', 'sort_order' => 2]);
        $catBerandaKeluarga = ServiceCategory::create(['group' => 'beranda', 'slug' => 'beranda-keluarga', 'name' => 'Family Life Community', 'sort_order' => 3]);

        // 7. Services Data (Ibadah)
        ChurchService::create([
            'category_id' => $catRaya->id,
            'title' => 'Ibadah Raya Minggu (General Service)',
            'description' => 'Ibadah raya perjumpaan dengan hadirat Allah melalui pujian penyembahan yang dinamis, pengajaran Firman yang mendalam dan relevan, serta persekutuan hangat seluruh keluarga jemaat AOCC.',
            'photo_path' => 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Setiap Minggu',
            'start_time' => '09:00',
            'end_time' => '11:00 WIB',
            'location' => 'Main Sanctuary Hall, Lantai 3',
            'contact_person_id' => $pakdheArdi->id,
            'is_default' => true,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catYouth->id,
            'title' => 'AOCC Youth Service (Ignite)',
            'description' => 'Wadah bagi mahasiswa dan anak muda untuk mengalami revival, menemukan panggilan hidup, dan memperlengkapi diri menjadi terang di era serba cepat.',
            'photo_path' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Setiap Sabtu',
            'start_time' => '17:00',
            'end_time' => '19:00 WIB',
            'location' => 'Upper Room Youth Center, Lantai 4',
            'contact_person_id' => $cpYouth->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catTeens->id,
            'title' => 'AOCC Teens (Brave Generation)',
            'description' => 'Pelayanan khusus remaja usia SMP - SMA dengan pendekatan kreatif, interaktif, musik kontemporer, dan bimbingan mentor yang penuh empati.',
            'photo_path' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Setiap Minggu',
            'start_time' => '10:30',
            'end_time' => '12:00 WIB',
            'location' => 'Teens Chapel, Lantai 2',
            'contact_person_id' => $cpYouth->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catKids->id,
            'title' => 'AOCC Kids Kingdom (Sekolah Minggu)',
            'description' => 'Pendidikan rohani anak yang menyenangkan dengan kurikulum berbasis Alkitab, drama, seni kreasi, dan guru-guru yang penuh kasih dan terlatih.',
            'photo_path' => 'https://images.unsplash.com/photo-1485546246426-74dc88dec4d9?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Setiap Minggu',
            'start_time' => '09:00',
            'end_time' => '10:45 WIB',
            'location' => 'Kids Playground & Hall, Lantai 1',
            'contact_person_id' => $cpKids->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catPria->id,
            'title' => 'Kaum Pria (Men of Honor)',
            'description' => 'Persekutuan dan pemuridan para imam keluarga, profesional, dan ayah untuk saling menguatkan dalam integritas, kepemimpinan ilahi, dan doa.',
            'photo_path' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Selasa ke-2 & ke-4',
            'start_time' => '19:00',
            'end_time' => '21:00 WIB',
            'location' => 'Fellowship Hall, Lantai 2',
            'contact_person_id' => $pakdheArdi->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catWanita->id,
            'title' => 'Kaum Wanita (Women of Grace)',
            'description' => 'Ruang persekutuan hangat bagi para ibu, wanita karir, dan mahasiswi untuk saling mendoakan, belajar Firman, dan bertumbuh dalam keanggunan Kristus.',
            'photo_path' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Setiap Kamis',
            'start_time' => '10:00',
            'end_time' => '12:00 WIB',
            'location' => 'Grace Meeting Lounge, Lantai 2',
            'contact_person_id' => $pakdheArdi->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        // 8. Services Data (Beranda / Komunitas)
        ChurchService::create([
            'category_id' => $catBerandaYouth->id,
            'title' => 'Beranda Youth: Life In Unity',
            'description' => 'Komunitas sel anak muda di mana kamu bisa menjadi diri sendiri, menemukan sahabat rohani sejati, bertumbuh dalam iman, dan belajar menuntaskan mimpi bersama Tuhan.',
            'photo_path' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Setiap Jumat',
            'start_time' => '19:00',
            'end_time' => '21:00 WIB',
            'location' => 'Multiple Cafe & Home Hubs',
            'contact_person_id' => $cpYouth->id,
            'is_default' => true,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catBerandaProM->id,
            'title' => 'Beranda Pro-M: Faith At Marketplace',
            'description' => 'Komunitas profesional muda, pekerja korporat, dan pengusaha startup untuk saling mengasah kepemimpinan berintegritas, etika kristiani, dan saling mendoakan karier.',
            'photo_path' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Rabu ke-1 & ke-3',
            'start_time' => '19:30',
            'end_time' => '21:30 WIB',
            'location' => 'Central Business District Hub & Online',
            'contact_person_id' => $pakdheArdi->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        ChurchService::create([
            'category_id' => $catBerandaKeluarga->id,
            'title' => 'Beranda Keluarga: Covenant Homes',
            'description' => 'Komunitas sel pasutri dan keluarga untuk membangun mezbah keluarga yang kokoh, mendidik anak di jalan Tuhan, dan memulihkan relasi keluarga.',
            'photo_path' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&w=1400&q=80',
            'day_of_week' => 'Sabtu ke-2 & ke-4',
            'start_time' => '18:00',
            'end_time' => '20:00 WIB',
            'location' => 'Family Homes (Area Jakarta Utara & Barat)',
            'contact_person_id' => $cpFamily->id,
            'is_default' => false,
            'is_active' => true,
        ]);

        // 9. Sermons
        Sermon::create([
            'title' => 'Menemukan Kedamaian di Tengah Badai Hidup',
            'slug' => 'menemukan-kedamaian-di-tengah-badai-hidup',
            'scripture_ref' => 'Markus 4 : 35-41',
            'preached_at' => now()->subDays(6)->toDateString(),
            'speaker_id' => $seniorPastor->id,
            'speaker_name' => 'Pdt. Jonathan Abraham, M.Th.',
            'service_category_id' => $catRaya->id,
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'thumbnail_path' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1000&q=80',
            'description' => 'Ketika perahu hidup kita diterpa gelombang ketidakpastian, ingatlah siapa yang ada bersama kita di dalam perahu tersebut.',
            'is_published' => true,
        ]);

        Sermon::create([
            'title' => 'Kekuatan dari Penyerahan Diri yang Utuh',
            'slug' => 'kekuatan-dari-penyerahan-diri-yang-utuh',
            'scripture_ref' => 'Roma 12 : 1-2',
            'preached_at' => now()->subDays(13)->toDateString(),
            'speaker_id' => $seniorPastor->id,
            'speaker_name' => 'Pdt. Jonathan Abraham, M.Th.',
            'service_category_id' => $catRaya->id,
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'thumbnail_path' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&w=1000&q=80',
            'description' => 'Penyembahan sejati dimulai saat kita mempersembahkan seluruh keberadaan kita sebagai korban yang hidup dan kudus bagi Tuhan.',
            'is_published' => true,
        ]);

        Sermon::create([
            'title' => 'Generasi Penakluk Raksasa (Brave & Unstoppable)',
            'slug' => 'generasi-penakluk-raksasa',
            'scripture_ref' => '1 Samuel 17 : 45-47',
            'preached_at' => now()->subDays(20)->toDateString(),
            'speaker_id' => null,
            'speaker_name' => 'Ev. Daniel Kurniawan',
            'service_category_id' => $catYouth->id,
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'thumbnail_path' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1000&q=80',
            'description' => 'Daud tidak melihat seberapa besarnya Goliat, tetapi ia melihat seberapa mahabesar Allah yang menyertai perjalanannya.',
            'is_published' => true,
        ]);

        Sermon::create([
            'title' => 'Membangun Fondasi yang Tak Tergoyahkan',
            'slug' => 'membangun-fondasi-yang-tak-tergoyahkan',
            'scripture_ref' => 'Matius 7 : 24-27',
            'preached_at' => now()->subDays(27)->toDateString(),
            'speaker_id' => $seniorPastor->id,
            'speaker_name' => 'Pdt. Jonathan Abraham, M.Th.',
            'service_category_id' => $catRaya->id,
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'thumbnail_path' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1000&q=80',
            'description' => 'Batu karang kehidupan kita adalah ketaatan melakukan Firman Tuhan, bukan sekadar menjadi pendengar yang mudah lupa.',
            'is_published' => true,
        ]);

        // 10. Events
        Event::create([
            'title' => 'AOCC Night of Worship: "Return to the Heart"',
            'slug' => 'aocc-night-of-worship-2026',
            'event_date' => now()->addDays(14)->toDateString(),
            'start_time' => '18:30',
            'end_time' => '21:30 WIB',
            'venue' => 'Main Sanctuary Hall AOCC',
            'description' => 'Malam perjumpaan doa, pujian, dan penyembahan intim tanpa batasan liturgi kaku. Datanglah dan rasakan pemulihan dari Bapa surgawi.',
            'link_url' => 'https://wa.me/6281288997766?text=Halo%20saya%20ingin%20hadir%20di%20AOCC%20Night%20of%20Worship',
            'link_label' => 'Reservasi Tempat',
            'contact_person_id' => $pakdheArdi->id,
            'cover_path' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1000&q=80',
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'AOCC Youth Camp 2026: UNSTOPPABLE GENERATION',
            'slug' => 'youth-camp-2026-unstoppable',
            'event_date' => now()->addDays(35)->toDateString(),
            'start_time' => '07:00 WIB',
            'end_time' => '3 Hari 2 Malam',
            'venue' => 'Highland Pine Resort & Camp, Puncak',
            'description' => 'Tiga hari retret pembentukan karakter, api kegerakan rohani, outdoor games, dan impartasi ilahi bagi seluruh pemuda dan remaja.',
            'link_url' => 'https://wa.me/6281311223344?text=Halo%20saya%20ingin%20mendaftar%20Youth%20Camp%202026',
            'link_label' => 'Daftar Youth Camp',
            'contact_person_id' => $cpYouth->id,
            'cover_path' => 'https://images.unsplash.com/photo-1478147427282-58a87a120781?auto=format&fit=crop&w=1000&q=80',
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Pro-M Marketplace Forum: "Leading with Integrity"',
            'slug' => 'pro-m-marketplace-forum-2026',
            'event_date' => now()->addDays(21)->toDateString(),
            'start_time' => '13:00',
            'end_time' => '16:00 WIB',
            'venue' => 'Auditorium Lantai 2 & Livestream',
            'description' => 'Seminar interaktif menghadirkan para CEO & Founder Kristen untuk membagikan wawasan kepemimpinan, integritas etis, dan strategi karier berbasis nilai Kerajaan Allah.',
            'link_url' => 'https://wa.me/6281288997766?text=Halo%20saya%20ingin%20mendaftar%20Seminar%20Pro-M',
            'link_label' => 'Info & Registrasi',
            'contact_person_id' => $pakdheArdi->id,
            'cover_path' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=1000&q=80',
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Family Dedication & Kebaktian Syukur Penyerahan Anak',
            'slug' => 'family-dedication-kebaktian-syukur',
            'event_date' => now()->addDays(28)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00 WIB',
            'venue' => 'Main Sanctuary Hall AOCC',
            'description' => 'Ibadah khusus penyerahan buah hati kepada Tuhan dan doa berkat persekutuan bagi orang tua untuk mendidik anak dalam pengenalan akan Tuhan.',
            'link_url' => 'https://wa.me/6281677889900?text=Halo%20saya%20ingin%20konsultasi%20penyerahan%20anak',
            'link_label' => 'Hubungi Koordinator',
            'contact_person_id' => $cpFamily->id,
            'cover_path' => 'https://images.unsplash.com/photo-1502086223501-7ea6ecd79368?auto=format&fit=crop&w=1000&q=80',
            'is_published' => true,
        ]);
    }
}
