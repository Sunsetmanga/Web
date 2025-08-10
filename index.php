<?php get_header(); ?>

<body class="min-h-screen bg-gradient-to-br from-[#0f0f0f] via-[#121212] to-[#18181b] text-white flex flex-col">

    <main class="flex-1">
        <header class="sr-only">
            <h1>Sunset Manga - Türkçe Manga Okuma Platformu</h1>
            <p>En güncel manga, manhwa ve manhua serilerini Türkçe olarak okuyabileceğiniz dijital manga platformu.</p>
        </header>

        <div class="w-full overflow-hidden relative hero-slider-wrapper sm:pt-8 pt-[6vw]">
            <button class="hero-prev absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center transition-all backdrop-blur-sm border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button class="hero-next absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center transition-all backdrop-blur-sm border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="hero-embla sm:px-2 md:px-3 lg:px-4">
                <div class="hero-embla__container flex gap-2 md:gap-3">
                    <?php
                    $hero_mangas = get_posts(array(
                        'post_type' => 'manga',
                        'posts_per_page' => 8,
                        'meta_key' => '_manga_views',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'post_status' => 'publish'
                    ));

                    foreach($hero_mangas as $manga) :
                        $cover_url = has_post_thumbnail($manga->ID) ? get_the_post_thumbnail_url($manga->ID, 'large') : 'https://manga-site-cdn.b-cdn.net/uploads/manga/covers/default-cover.webp';
                        $manga_type = get_post_meta($manga->ID, '_manga_type', true);
                        $type_labels = array(
                            'manga' => 'Manga',
                            'manhwa' => 'Manhwa',
                            'manhua' => 'Manhua',
                            'webtoon' => 'Webtoon',
                            'novel' => 'Novel'
                        );
                        $type_text = !empty($manga_type) && isset($type_labels[$manga_type]) ? $type_labels[$manga_type] : 'Manhwa';
                        
                        $description = '';
                        if (!empty($manga->post_excerpt)) {
                            $description = wp_trim_words($manga->post_excerpt, 12, '...');
                        } elseif (!empty($manga->post_content)) {
                            $description = wp_trim_words(strip_tags($manga->post_content), 12, '...');
                        } else {
                            $description = 'Bu harika manga serisini keşfedin ve okumaya başlayın.';
                        }
                    ?>
                    <div class="hero-embla__slide group flex-[0_0_80%] min-w-0 sm:flex-[0_0_50%] md:flex-[0_0_40%] lg:flex-[0_0_33.33%] xl:flex-[0_0_20%] 2xl:flex-[0_0_20%]">
                        <div class="px-4 sm:px-0">
                        <a draggable="false" class="block overflow-hidden relative w-full max-w-[300px] mx-auto sm:max-w-none rounded-xl" href="<?php echo get_permalink($manga->ID); ?>">
                                <div draggable="false" class="relative w-full h-[400px] sm:h-[450px] md:h-[500px] lg:h-[550px] xl:h-[600px] bg-center bg-cover bg-zinc-800/30 rounded-xl shadow-xl transition-all duration-300 group-hover:shadow-2xl group-hover:scale-105" 
                                     style="background-image: url('<?php echo esc_url($cover_url); ?>');">
                                    
                                    <div class="absolute top-3 left-3 z-10">
                                        <span class="bg-black/30 backdrop-blur-sm border border-white/40 px-2 py-1 rounded-md text-xs font-semibold text-white uppercase tracking-wide shadow-sm">
                                            <?php echo esc_html($type_text); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/95 via-black/80 to-transparent p-4 rounded-b-xl">
                                        
                                        <?php
                                        $manga_status = get_post_meta($manga->ID, '_manga_status', true);
                                        if (!empty($manga_status)) :
                                            $status_text = '';
                                            $status_dot_class = '';
                                            switch ($manga_status) {
                                                case 'ongoing':
                                                    $status_text = 'DEVAM EDİYOR';
                                                    $status_dot_class = 'bg-green-400';
                                                    break;
                                                case 'finished':
                                                    $status_text = 'TAMAMLANDI';
                                                    $status_dot_class = 'bg-blue-400';
                                                    break;
                                                case 'paused':
                                                    $status_text = 'DURDURULDU';
                                                    $status_dot_class = 'bg-yellow-400';
                                                    break;
                                            }
                                        ?>
                                            <div class="mb-2">
                                                <span class="inline-flex items-center gap-2 rounded-full bg-black/40 backdrop-blur-sm px-3 py-1 text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    <span class="h-2 w-2 rounded-full <?php echo esc_attr($status_dot_class); ?>"></span>
                                                    <span><?php echo esc_html($status_text); ?></span>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <h3 class="text-white font-bold text-base sm:text-lg mb-2 line-clamp-2 leading-tight">
                                            <?php echo esc_html($manga->post_title); ?>
                                        </h3>

                                        <p class="text-gray-300 text-xs sm:text-sm line-clamp-3 leading-relaxed">
                                            <?php echo esc_html($description); ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="w-full 2xl:max-w-[100rem] max-w-6xl mx-auto sm:px-4 px-[4vw] py-8">
            <div class="border border-blue-500 rounded-lg p-4 mb-8 bg-[#1a1a1a]/50">
                <h3 class="text-center text-red-500 font-bold mb-2 uppercase">Önemli Duyuru</h3>
                <p class="text-center text-gray-300">Site hala yapım aşamasındadır.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdAuIQ0CsEtoYNpnuFsKIse7gx6pk-Qgd2wSS5yKo5ANompqQ/viewform" target="_blank" rel="noopener noreferrer" class="bg-[#2a2a2e] hover:bg-[#3a3a3e] transition-colors p-5 rounded-xl flex justify-between items-center">
                    <div>
                        <h4 class="text-white font-bold text-lg">Ekibe Katıl</h4>
                        <p class="text-gray-400 text-sm">Aramza Katıl</p>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-black/20 border border-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="17" y1="11" x2="23" y2="11"></line></svg>
                    </div>
                </a>

                <div class="bg-[#2a2a2e] p-5 rounded-xl flex justify-between items-center">
                    <div>
                        <h4 class="text-white font-bold text-lg">Discord</h4>
                        <p class="text-gray-400 text-sm">Sunucumuza Katıl</p>
                    </div>
                    <a href="https://discord.com/invite/bV8zjQxp4T" target="_blank" rel="noopener noreferrer" class="bg-[#5865F2] hover:bg-[#4752C4] text-white font-semibold py-3 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-discord" viewBox="0 0 16 16">
                            <path d="M13.545 2.907a13.2 13.2 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.2 12.2 0 0 0-3.658 0 8 8 0 0 0-.412-.833.05.05 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.04.04 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032q.003.022.021.037a13.3 13.3 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019q.463-.63.818-1.329a.05.05 0 0 0-.01-.059l-.018-.011a9 9 0 0 1-1.248-.595.05.05 0 0 1-.02-.066l.015-.019q.127-.095.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.05.05 0 0 1 .053.007q.121.1.248.195a.05.05 0 0 1-.004.085 8 8 0 0 1-1.249.594.05.05 0 0 0-.03.03.05.05 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.2 13.2 0 0 0 4.001-2.02.05.05 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.03.03 0 0 0-.02-.019m-8.198 7.307c-.789 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612m5.316 0c-.788 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612"/>
                        </svg>
                        <span>Discord</span>
                    </a>
                </div>

                <div class="bg-[#2a2a2e] p-5 rounded-xl flex justify-between items-center">
                    <div>
                        <h4 class="text-white font-bold text-lg">Instagram</h4>
                        <p class="text-gray-400 text-sm">Bizi Takip Et</p>
                    </div>
                    <a href="https://www.instagram.com/sunsetmangaa" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:opacity-90 text-white font-semibold py-3 px-4 rounded-lg flex items-center gap-2 transition-opacity">
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163m0-2.163C8.74 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.74 0 12s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.74 24 12 24s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98C23.986 15.667 24 15.26 24 12s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98C15.667.014 15.26 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z"></path></svg>
                        <span>Instagram</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full 2xl:max-w-[100rem] max-w-6xl mx-auto sm:px-4 px-[4vw] sm:py-8 py-[6vw]">
            <div class="flex flex-col 2xl:flex-row gap-8 lg:gap-10">
                
                <div class="flex-1 flex flex-col gap-4 sm:gap-6">
                    <div class="flex justify-between items-center">
                        <h2 class="sm:text-2xl text-[5.5vw] font-bold text-white">Son Güncellemeler</h2>
                        <a class="flex sm:gap-2 gap-[2vw] sm:text-base text-[4vw] justify-center items-center text-white" href="<?php echo get_post_type_archive_link('manga'); ?>">
                            <span>Tümünü Gör</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="iconify iconify--material-symbols w-5 h-5" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m14.475 12l-7.35-7.35q-.375-.375-.363-.888t.388-.887t.888-.375t.887.375l7.675 7.7q.3.3.45.675t.15.75t-.15.75t-.45.675l-7.7 7.7q-.375.375-.875.363T7.15 21.1t-.375-.888t.375-.887z"></path></svg>
                        </a>
                    </div>
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-2 grid-cols-1 gap-4">
                        <?php
                        global $wpdb;
                        $recently_updated_manga_ids = $wpdb->get_col( $wpdb->prepare("
                            SELECT manga.ID
                            FROM {$wpdb->posts} AS manga
                            INNER JOIN {$wpdb->postmeta} AS meta ON manga.ID = meta.meta_value AND meta.meta_key = '_episode_manga'
                            INNER JOIN {$wpdb->posts} AS episode ON meta.post_id = episode.ID AND episode.post_type = 'episode' AND episode.post_status = 'publish'
                            WHERE manga.post_type = 'manga' AND manga.post_status = 'publish'
                            GROUP BY manga.ID
                            ORDER BY MAX(episode.post_date) DESC
                            LIMIT %d
                        ", 9) );

                        if ($recently_updated_manga_ids) :
                            foreach($recently_updated_manga_ids as $manga_id) :
                                $manga_post = get_post($manga_id);
                                $manga_title = $manga_post->post_title;
                                $cover_url = has_post_thumbnail($manga_id) ? get_the_post_thumbnail_url($manga_id, 'medium') : 'https://manga-site-cdn.b-cdn.net/uploads/manga/covers/default-cover.jpeg';
                                
                                $manga_type = get_post_meta($manga_id, '_manga_type', true);
                                $type_labels = array(
                                    'manga' => 'Manga',
                                    'manhwa' => 'Manhwa',
                                    'manhua' => 'Manhua',
                                    'webtoon' => 'Webtoon',
                                    'novel' => 'Novel'
                                );
                                $type_text = !empty($manga_type) && isset($type_labels[$manga_type]) ? $type_labels[$manga_type] : 'Manhwa';

                                $latest_episodes = get_posts(array(
                                    'post_type' => 'episode',
                                    'posts_per_page' => 4,
                                    'meta_query' => array(
                                        array(
                                            'key' => '_episode_manga',
                                            'value' => $manga_id,
                                            'compare' => '='
                                        )
                                    ),
                                    'orderby' => 'post_date',
                                    'order' => 'DESC'
                                ));
                        ?>
                        <div class="group latest-poster bg-white/10 hover:bg-white/20 rounded-lg transition-all border border-white/5 flex aspect-video overflow-hidden relative">
                            <a class="flex flex-col justify-between rounded-l-lg transition-all aspect-[0.75/1] h-full bg-white/10 bg-no-repeat bg-cover bg-center relative" href="<?php echo get_permalink($manga_id); ?>" style="background-image: url('<?php echo esc_url($cover_url); ?>');">
                                <div class="flex flex-wrap gap-1 m-1">
                                    <span class="bg-zinc-950/50 border border-white/5 backdrop-blur-3xl w-fit h-fit px-1 rounded-md text-xs text-white"><?php echo esc_html($type_text); ?></span>
                                </div>
                            </a>
                            <div class="flex flex-col sm:py-2.5 sm:px-3.5 py-[2vw] px-[2.5vw] w-full">
                                <div class="grid gap-1.5 mb-2">
                                    <a class="grid" href="<?php echo get_permalink($manga_id); ?>">
                                        <h3 class="h-10 sm:h-12 text-base sm:text-lg line-clamp-2 leading-5 sm:leading-6 break-words text-white flex items-start"><?php echo esc_html($manga_title); ?></h3>
                                    </a>
                                </div>
                                <div class="grid">
                                    <div class="grid text-xs sm:text-sm divide-y divide-white/20 divide-dashed">
                                        <?php 
                                        if ($latest_episodes) :
                                            foreach ($latest_episodes as $episode_post) : 
                                                $episode_id = $episode_post->ID;
                                                $episode_number = get_post_meta($episode_id, '_episode_number', true) ?: '?';
                                                $time_ago = human_time_diff(strtotime($episode_post->post_date), current_time('timestamp')) . ' önce';
                                                
                                                $is_new = false;
                                                $episode_time = strtotime($episode_post->post_date);
                                                $current_time = current_time('timestamp');
                                                if (($current_time - $episode_time) < (3 * 86400)) {
                                                    $is_new = true;
                                                }
                                        ?>
                                        <a class="flex justify-between items-center leading-none gap-1 py-1.5 hover:-mx-2 hover:px-2 hover:bg-white/10 visited:text-zinc-500 hover:rounded-lg transition-all" href="<?php echo get_permalink($episode_id); ?>">
                                            <span class="flex justify-start items-center h-4 gap-1.5 w-full overflow-hidden">
                                                <div class="truncate leading-4 -my-0.5 text-white text-sm">Bölüm <?php echo esc_html($episode_number); ?></div>
                                                <?php if ($is_new) : ?>
                                                    <span class="bg-white/20 text-white text-[0.6rem] font-bold px-1.5 py-0.5 rounded-md">YENİ</span>
                                                <?php endif; ?>
                                            </span>
                                            <span class="text-[11px] whitespace-nowrap text-white/70"><?php echo esc_html($time_ago); ?></span>
                                        </a>
                                        <?php 
                                            endforeach;
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>

                <div class="max-w-[25rem] w-full 2xl:flex flex-col hidden gap-4 h-fit">
                    <div class="flex justify-between items-center h-fit">
                        <h2 class="sm:text-2xl text-[5.5vw] font-bold text-white">Trendler</h2>
                    </div>
                    <div class="grid gap-4 h-fit">
                        <?php
                        $popular_mangas = get_posts(array(
                            'post_type' => 'manga',
                            'posts_per_page' => 4,
                            'meta_key' => '_manga_views',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'post_status' => 'publish'
                        ));
                        
                        $rank = 1;
                        foreach($popular_mangas as $manga) :
                            $genres = wp_get_post_terms($manga->ID, 'manga_genre', array('fields' => 'names'));
                            $genre_text = !empty($genres) ? implode(', ', array_slice($genres, 0, 2)) : 'Tarihi, Dövüş Sanatları';
                            $cover_url = has_post_thumbnail($manga->ID) ? get_the_post_thumbnail_url($manga->ID, 'medium') : 'https://manga-site-cdn.b-cdn.net/uploads/manga/covers/default-cover.jpeg';
                        ?>
                        <?php if($rank == 1) : ?>
                        <div class="group bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/5 grid overflow-hidden">
                            <a class="grid transition-all aspect-[2/1] w-full bg-white/10 bg-no-repeat bg-cover bg-center" href="<?php echo get_permalink($manga->ID); ?>" style="background-image: url('<?php echo esc_url($cover_url . '?w=600'); ?>');">
                                <div class="flex flex-wrap gap-1 m-1">
                                    <span class="bg-zinc-950/50 border border-white/5 backdrop-blur-3xl w-fit h-fit px-1 rounded-md text-xs capitalize text-white"><?php echo esc_html(explode(',', $genre_text)[0]); ?></span>
                                </div>
                            </a>
                            <div class="flex gap-3.5 items-center justify-start sm:py-2.5 sm:px-3.5 py-[2vw] px-[2.5vw] w-full">
                                <div class="text-3xl font-bold text-white">#<?php echo $rank; ?></div>
                                <div class="grid h-fit gap-1">
                                    <a class="grid" href="<?php echo get_permalink($manga->ID); ?>">
                                        <h3 class="h-fit text-base line-clamp-2 leading-5 break-words text-white"><?php echo esc_html($manga->post_title); ?></h3>
                                    </a>
                                    <div class="text-xs flex flex-wrap gap-1 leading-none text-white/70">
                                        <?php echo esc_html($genre_text); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="group flex gap-3.5 sm:p-3.5 p-[2.5vw] items-center bg-white/10 hover:bg-white/20 rounded-xl transition-all aspect-[2/0.5] border border-white/5 overflow-hidden">
                            <a class="grid transition-all aspect-[0.75/1] border border-white/10 h-full rounded-lg bg-white/10 bg-no-repeat bg-cover bg-center" href="<?php echo get_permalink($manga->ID); ?>" style="background-image: url('<?php echo esc_url($cover_url . '?w=80'); ?>');"></a>
                                <div class="text-3xl font-bold text-white"><?php echo $rank; ?></div>
                                <div class="grid h-fit gap-1">
                                    <a class="grid" href="<?php echo get_permalink($manga->ID); ?>">
                                        <h3 class="h-fit text-base truncate leading-5 break-words text-white"><?php echo esc_html($manga->post_title); ?></h3>
                                    </a>
                                    <div class="text-xs flex flex-wrap gap-1 leading-none text-white/70">
                                        <?php echo esc_html($genre_text); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $rank++; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .hero-embla {
            overflow: hidden;
            --slide-spacing: 0.5rem;
            cursor: grab;
        }
        .hero-embla.is-dragging {
            cursor: grabbing;
            user-select: none;
            -webkit-user-select: none;
        }
        .hero-embla__container {
            display: flex;
            touch-action: pan-y;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            will-change: transform;
        }
        .hero-embla__slide {
            position: relative;
            padding-left: var(--slide-spacing);
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .hero-embla__slide {
                padding-left: 0.25rem;
            }
            .hero-embla {
                --slide-spacing: 0.25rem;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const emblaNode = document.querySelector('.hero-embla');
        if (!emblaNode) return;

        const container = emblaNode.querySelector('.hero-embla__container');
        const slides = Array.from(container.querySelectorAll('.hero-embla__slide'));
        const prevBtn = document.querySelector('.hero-prev');
        const nextBtn = document.querySelector('.hero-next');

        if (slides.length === 0) return;

        let currentIndex = 0;
        let isDragging = false;
        let hasDragged = false;
        let startPos = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID;
        let slideWidth = 0;

        function getSlideWidth() {
            if (slides.length > 0) {
                return slides[0].offsetWidth + (parseFloat(getComputedStyle(emblaNode).getPropertyValue('--slide-spacing')) || 8);
            }
            return 0;
        }
        
        function getVisibleSlidesCount() {
            const screenWidth = window.innerWidth;
            if (screenWidth < 640) return 1.25;
            if (screenWidth < 768) return 2;
            if (screenWidth < 1024) return 2.5;
            if (screenWidth < 1280) return 3;
            return 5;
        }
        
        function getStepCount() {
            const screenWidth = window.innerWidth;
            if (screenWidth < 768) return 1;
            return 2;
        }

        function getMaxIndex() {
            return Math.max(0, slides.length - Math.floor(getVisibleSlidesCount()));
        }

        function setPosition() {
            container.style.transform = `translateX(${currentTranslate}px)`;
        }

        function setSliderToIndex() {
            const maxIndex = getMaxIndex();
            currentIndex = Math.max(0, Math.min(currentIndex, maxIndex));
            currentTranslate = -currentIndex * slideWidth;
            prevTranslate = currentTranslate;
            container.style.transition = 'transform 0.5s ease-out';
            setPosition();
        }

        function dragStart(event) {
            isDragging = true;
            hasDragged = false;
            startPos = getPositionX(event);
            animationID = requestAnimationFrame(animation);
            container.style.transition = '';
            emblaNode.classList.add('is-dragging');
        }

        function dragMove(event) {
            if (isDragging) {
                const currentPosition = getPositionX(event);
                const movedBy = currentPosition - startPos;
                
                if (!hasDragged && Math.abs(movedBy) > 10) {
                    hasDragged = true;
                }
                currentTranslate = prevTranslate + movedBy;
            }
        }

        function dragEnd(event) {
            cancelAnimationFrame(animationID);
            isDragging = false;
            emblaNode.classList.remove('is-dragging');
            
            const movedBy = currentTranslate - prevTranslate;

            if (hasDragged) {
                if (movedBy < -50 && currentIndex < getMaxIndex()) {
                    currentIndex += 1;
                }
                if (movedBy > 50 && currentIndex > 0) {
                    currentIndex -= 1;
                }
            }
            
            setSliderToIndex();
        }

        function animation() {
            if (hasDragged) {
                setPosition();
            }
            if (isDragging) requestAnimationFrame(animation);
        }

        function getPositionX(event) {
            return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
        }

        function onResize() {
            slideWidth = getSlideWidth();
            setSliderToIndex();
        }

        function nextSlide() {
            const maxIndex = getMaxIndex();
            currentIndex = Math.min(currentIndex + getStepCount(), maxIndex);
            setSliderToIndex();
        }

        function prevSlide() {
            currentIndex = Math.max(currentIndex - getStepCount(), 0);
            setSliderToIndex();
        }

        slides.forEach(slide => {
            const link = slide.querySelector('a');
            if (link) {
                link.addEventListener('click', (e) => {
                    if (hasDragged) {
                        e.preventDefault();
                    }
                });
            }
        });

        emblaNode.addEventListener('mousedown', dragStart);
        emblaNode.addEventListener('touchstart', dragStart, { passive: true });

        emblaNode.addEventListener('mousemove', dragMove);
        emblaNode.addEventListener('touchmove', dragMove, { passive: true });

        emblaNode.addEventListener('mouseup', dragEnd);
        emblaNode.addEventListener('mouseleave', dragEnd);
        emblaNode.addEventListener('touchend', dragEnd);

        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        
        window.addEventListener('resize', onResize);
        
        onResize();
    });
    </script>
</body>

<?php get_footer(); ?>