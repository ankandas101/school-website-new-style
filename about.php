<?php
include 'includes/db.php';
include 'includes/header.php';

$schoolStats = new SchoolStatistics($conn);
$statistics = $schoolStats->getActive();
?>

<main class="bg-gray-50 overflow-hidden">
  <!-- ===== HERO SECTION ===== -->
  <section class="relative min-h-[85vh] flex items-end justify-center overflow-hidden" data-aos="fade">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
      <?php if (!empty($school_info['banner'])): ?>
        <img src="assets/images/<?php echo htmlspecialchars($school_info['banner']); ?>" class="w-full h-full object-cover" alt="স্কুল ব্যানার">
      <?php else: ?>
        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
      <?php endif; ?>
      <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-gray-900/30"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 max-w-6xl mx-auto px-4 pb-20 pt-32">
      <!-- Floating Glass Logo Card -->
      <div class="mb-12" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-[24px] p-6 inline-flex items-center gap-6 shadow-2xl">
          <?php if (!empty($school_info['logo'])): ?>
            <div class="w-20 h-20 bg-white rounded-2xl p-2 shadow-lg">
              <img src="assets/images/<?php echo htmlspecialchars($school_info['logo']); ?>" class="w-full h-full object-contain" alt="স্কুল লোগো">
            </div>
          <?php endif; ?>
          <div>
            <p class="text-green-400 text-sm font-semibold tracking-wider uppercase mb-1">প্রতিষ্ঠান সম্পর্কে</p>
            <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
              <?php echo htmlspecialchars($school_info['school_name'] ?? 'বিদ্যালয়'); ?>
            </h1>
          </div>
        </div>
      </div>

      <!-- Hero Text -->
      <div data-aos="fade-up" data-aos-delay="200">
        <p class="text-xl md:text-2xl text-gray-200 max-w-3xl leading-relaxed">
          আমাদের শিক্ষাপ্রতিষ্ঠানে প্রতিভাকে উন্মেষ করা, মানবিক মূল্যবোধ গড়ে তোলা এবং ভবিষ্যতের নেতাকে তৈরি করা আমাদের একমাত্র লক্ষ্য।
        </p>
        <?php if (!empty($school_info['established']) || !empty($school_info['eiin']) || !empty($school_info['mpo_code']) || !empty($school_info['school_code'])): ?>
          <div class="mt-8 flex flex-wrap items-center gap-4">
            <?php if (!empty($school_info['established'])): ?>
              <span class="px-4 py-2 bg-green-600/20 border border-green-500/30 rounded-full text-green-300 text-sm font-semibold">
                প্রতিষ্ঠিত: <?php echo htmlspecialchars($school_info['established']); ?>
              </span>
            <?php endif; ?>
            <?php if (!empty($school_info['eiin'])): ?>
              <span class="px-4 py-2 bg-blue-600/20 border border-blue-500/30 rounded-full text-blue-300 text-sm font-semibold">
                EIIN: <?php echo htmlspecialchars($school_info['eiin']); ?>
              </span>
            <?php endif; ?>
            <?php if (!empty($school_info['mpo_code'])): ?>
              <span class="px-4 py-2 bg-purple-600/20 border border-purple-500/30 rounded-full text-purple-300 text-sm font-semibold">
                MPO Code: <?php echo htmlspecialchars($school_info['mpo_code']); ?>
              </span>
            <?php endif; ?>
            <?php if (!empty($school_info['school_code'])): ?>
              <span class="px-4 py-2 bg-orange-600/20 border border-orange-500/30 rounded-full text-orange-300 text-sm font-semibold">
                School Code: <?php echo htmlspecialchars($school_info['school_code']); ?>
              </span>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </section>

  <!-- ===== STATISTICS SECTION ===== -->
  <section class="py-16 bg-white" data-aos="fade-up">
    <div class="max-w-6xl mx-auto px-4 -mt-16 relative z-20">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <?php 
        $about_stat_colors = [
          ['bg' => '#e8f5ee', 'stroke' => '#118847'],
          ['bg' => '#eff6ff', 'stroke' => '#3b82f6'],
          ['bg' => '#faf5ff', 'stroke' => '#8b5cf6'],
          ['bg' => '#fff7ed', 'stroke' => '#f59e0b'],
          ['bg' => '#f0fdf4', 'stroke' => '#22c55e'],
          ['bg' => '#fce7f3', 'stroke' => '#ec4899'],
        ];
        $about_stat_index = 0;
        if ($statistics && $statistics->num_rows > 0): 
          while ($stat = $statistics->fetch_assoc()): 
            $color = $about_stat_colors[$about_stat_index % count($about_stat_colors)];
            $about_stat_index++;
            // Default icons for about page
            $about_default_icons = [
              '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
              '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
              '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
              '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ];
            $about_icon_path = !empty($stat['icon']) ? $stat['icon'] : $about_default_icons[$about_stat_index % count($about_default_icons)];
        ?>
        <div class="bg-white rounded-[24px] shadow-xl border border-gray-100 p-4 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-10 h-10 rounded-2xl flex items-center justify-center mb-3 mx-auto" style="background:<?php echo $color['bg']; ?>;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke:<?php echo $color['stroke']; ?>;">
              <?php echo $about_icon_path; ?>
            </svg>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($stat['value']); ?><?php echo !empty($stat['suffix']) ? htmlspecialchars($stat['suffix']) : ''; ?></div>
          <div class="text-gray-500 text-xs font-medium"><?php echo htmlspecialchars($stat['title']); ?></div>
        </div>
        <?php endwhile; else: ?>
        <!-- Fallback static stats -->
        <div class="bg-white rounded-[24px] shadow-xl border border-gray-100 p-4 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-10 h-10 bg-green-50 rounded-2xl flex items-center justify-center mb-3 mx-auto">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1">২৫০০+</div>
          <div class="text-gray-500 text-xs font-medium">সক্রিয় শিক্ষার্থী</div>
        </div>
        <div class="bg-white rounded-[24px] shadow-xl border border-gray-100 p-4 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center mb-3 mx-auto">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1">৬০+</div>
          <div class="text-gray-500 text-xs font-medium">স্কিল্ড শিক্ষক</div>
        </div>
        <div class="bg-white rounded-[24px] shadow-xl border border-gray-100 p-4 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center mb-3 mx-auto">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1">৯৮%</div>
          <div class="text-gray-500 text-xs font-medium">ফলাফল হার</div>
        </div>
        <div class="bg-white rounded-[24px] shadow-xl border border-gray-100 p-4 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-10 h-10 bg-orange-50 rounded-2xl flex items-center justify-center mb-3 mx-auto">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1">৫০+</div>
          <div class="text-gray-500 text-xs font-medium">বছর অভিজ্ঞতা</div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>



  <!-- ===== ABOUT DESCRIPTION ===== -->
  <section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div data-aos="fade-right">
          <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">আমাদের সম্পর্কে</span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
            আমরা কেন ভিন্ন?
          </h2>
          <div class="prose prose-lg text-gray-600 leading-relaxed">
            <p class="mb-4">
              <?php echo nl2br(htmlspecialchars($school_info['about'] ?? 'আমাদের বিদ্যালয় শিক্ষার ক্ষেত্রে একইসাথে প্রাচীন ঐতিহ্য ও আধুনিক প্রযুক্তির সমন্বয় সাধন করে। আমরা শুধুমাত্র পাঠ্যবইভিত্তিক শিক্ষারই সীমাবদ্ধ নই, বরং শিক্ষার্থীদের মানসিক, সামাজিক ও শারীরিক বিকাশের জন্য বিভিন্ন কর্মকাণ্ড পরিচালনা করি।')); ?>
            </p>
          </div>
        </div>
        <div data-aos="fade-left">
          <div class="relative max-w-sm mx-auto">
            <div class="absolute -top-4 -left-4 w-48 h-48 bg-green-100 rounded-full -z-10"></div>
            <div class="absolute -bottom-4 -right-4 w-48 h-48 bg-blue-100 rounded-full -z-10"></div>
            <div class="bg-gray-100 rounded-[24px] aspect-square overflow-hidden">
              <?php if (!empty($school_info['banner'])): ?>
                <img src="assets/images/<?php echo htmlspecialchars($school_info['banner']); ?>" class="w-full h-full object-cover" alt="স্কুল">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-500 to-blue-600">
                  <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== MISSION, VISION & VALUES ===== -->
  <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-6xl mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16" data-aos="fade-up">
        <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">মূলনীতি</span>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Mission, Vision & Values</h2>
        <p class="text-gray-500 max-w-2xl mx-auto text-lg">
          আমাদের লক্ষ্য, দৃষ্টিভঙ্গি ও মূল্যবোধ
        </p>
      </div>

      <!-- Cards Grid -->
      <div class="grid md:grid-cols-3 gap-8">
        <!-- Mission Card -->
        <div class="bg-white rounded-[24px] p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
          <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition-colors duration-300">
            <svg class="w-8 h-8 text-green-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Mission</h3>
          <p class="text-gray-500 leading-relaxed">
            গুণগত শিক্ষার মাধ্যমে সৃজনশীল, দায়িত্বশীল এবং নৈতিক মূল্যবোধ সম্পন্ন নাগরিক তৈরি করা।
          </p>
        </div>

        <!-- Vision Card -->
        <div class="bg-white rounded-[24px] p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
          <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-300">
            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Vision</h3>
          <p class="text-gray-500 leading-relaxed">
            জাতীয় ও আন্তর্জাতিক মানদণ্ডে এক শীর্ষস্থানীয় শিক্ষাপ্রতিষ্ঠান হিসেবে প্রতিষ্ঠিত হওয়া।
          </p>
        </div>

        <!-- Values Card -->
        <div class="bg-white rounded-[24px] p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
          <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors duration-300">
            <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Values</h3>
          <p class="text-gray-500 leading-relaxed">
            সততা, নৈতিকতা, সহযোগিতা, শ্রদ্ধা এবং অগ্রগতি আমাদের মূল মূল্যবোধ।
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== PRINCIPAL'S MESSAGE ===== -->
  <section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
      <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-[32px] p-8 md:p-12">
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="order-2 md:order-1" data-aos="fade-right">
            <span class="inline-block px-4 py-1.5 bg-green-600 text-white rounded-full text-sm font-semibold mb-4">প্রধান শিক্ষকের বাণী</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
              প্রধান শিক্ষকের বাণী
            </h2>
            <div class="space-y-4 text-gray-600 leading-relaxed mb-8">
              <p>
                প্রিয় অভিভাবক ও শিক্ষার্থী,
              </p>
              <p>
                শিক্ষা হলো মানব জীবনের আলো। আমাদের বিদ্যালয়ে আমরা শুধুমাত্র পাঠ্যপুস্তকের শিক্ষারই সীমাবদ্ধ নই, বরং প্রতিটি শিক্ষার্থীর অন্তর্নিহিত প্রতিভাকে উন্মেষ করার চেষ্টা করি।
              </p>
              <p>
                আধুনিক প্রযুক্তির সাথে সঙ্গতি রেখে আমরা আমাদের শিক্ষার্থীদেরকে ২১শ শতাব্দীর জন্য প্রস্তুত করছি। আমাদের শিক্ষকবৃন্দ সবসময় শিক্ষার্থীদের পাশে থাকেন।
              </p>
            </div>
            <div class="flex items-center gap-4">
              <div>
                <div class="text-xl font-bold text-gray-900">প্রধান শিক্ষক</div>
                <div class="text-gray-500"><?php echo htmlspecialchars($school_info['school_name'] ?? 'বিদ্যালয়'); ?></div>
              </div>
            </div>
          </div>
          <div class="order-1 md:order-2" data-aos="fade-left">
            <div class="relative">
              <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-blue-600 rounded-[32px] rotate-3"></div>
              <div class="relative bg-white rounded-[32px] p-4 shadow-xl">
                <?php if (!empty($school_info['logo'])): ?>
                  <div class="bg-gray-100 rounded-[24px] aspect-square flex items-center justify-center overflow-hidden">
                    <img src="assets/images/<?php echo htmlspecialchars($school_info['logo']); ?>" class="w-3/4 h-3/4 object-contain" alt="প্রধান শিক্ষক">
                  </div>
                <?php else: ?>
                  <div class="bg-gradient-to-br from-green-500 to-blue-600 rounded-[24px] aspect-square flex items-center justify-center">
                    <svg class="w-32 h-32 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== WHY CHOOSE US ===== -->
  <section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16" data-aos="fade-up">
        <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">কেন আমরা?</span>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why Choose Us</h2>
        <p class="text-gray-500 max-w-2xl mx-auto text-lg">
          আমাদের বিশেষত্ব যা আমাদেরকে অন্যদের থেকে আলাদা করে
        </p>
      </div>

      <!-- Features Grid -->
      <div class="grid md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="bg-white rounded-[24px] p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
          <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">আধুনিক করিকিউলাম</h3>
          <p class="text-gray-500 leading-relaxed">
            NCTB করিকিউলামের সাথে আধুনিক প্রযুক্তি ও প্রায়োগিক শিক্ষার সমন্বয়।
          </p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white rounded-[24px] p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
          <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">দক্ষ শিক্ষকবৃন্দ</h3>
          <p class="text-gray-500 leading-relaxed">
            অভিজ্ঞ, প্রশিক্ষিত ও প্রতিশ্রুতিবদ্ধ শিক্ষকদের দল।
          </p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white rounded-[24px] p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
          <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">সহ-কার্যক্রম</h3>
          <p class="text-gray-500 leading-relaxed">
            খেলাধুলা, সাংস্কৃতিক অনুষ্ঠান, বিজ্ঞান মেলা ও বেশ কিছু কার্যক্রম।
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== ADMISSION CTA ===== -->
  <section class="py-20 bg-gradient-to-br from-green-600 via-green-700 to-blue-700">
    <div class="max-w-4xl mx-auto px-4 text-center" data-aos="fade-up">
      <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
        আপনার সন্তানের ভবিষ্যৎ গড়ে তুলুন
      </h2>
      <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
        ভর্তি চলমান! আজই যোগাযোগ করুন এবং আপনার সন্তানকে আমাদের বিদ্যালয়ের পরিবারের একজন করে তুলুন।
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="admission.php" class="inline-flex items-center justify-center gap-2 bg-white text-green-700 px-8 py-4 rounded-[16px] font-bold text-lg hover:bg-gray-100 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          ভর্তি তথ্য দেখুন
        </a>
        <a href="contact.php" class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white text-white px-8 py-4 rounded-[16px] font-bold text-lg hover:bg-white/10 transition-all duration-300">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          যোগাযোগ করুন
        </a>
      </div>
    </div>
  </section>

  <!-- ===== CONTACT INFO ===== -->
  <?php if (!empty($school_info['address']) || !empty($school_info['phone']) || !empty($school_info['email'])): ?>
  <section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
      <div class="grid md:grid-cols-3 gap-6">
        <?php if (!empty($school_info['address'])): ?>
        <div class="flex items-center gap-4 bg-gray-50 rounded-[24px] p-6" data-aos="fade-up" data-aos-delay="100">
          <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 mb-1">ঠিকানা</div>
            <div class="text-gray-900 font-medium"><?php echo htmlspecialchars($school_info['address']); ?></div>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($school_info['phone'])): ?>
        <div class="flex items-center gap-4 bg-gray-50 rounded-[24px] p-6" data-aos="fade-up" data-aos-delay="200">
          <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 mb-1">ফোন</div>
            <a href="tel:<?php echo htmlspecialchars($school_info['phone']); ?>" class="text-gray-900 font-medium hover:text-blue-600 transition-colors">
              <?php echo htmlspecialchars($school_info['phone']); ?>
            </a>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($school_info['email'])): ?>
        <div class="flex items-center gap-4 bg-gray-50 rounded-[24px] p-6" data-aos="fade-up" data-aos-delay="300">
          <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 mb-1">ইমেইল</div>
            <a href="mailto:<?php echo htmlspecialchars($school_info['email']); ?>" class="text-gray-900 font-medium hover:text-purple-600 transition-colors">
              <?php echo htmlspecialchars($school_info['email']); ?>
            </a>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php include_once 'includes/footer.php'; ?>
