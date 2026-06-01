<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-3xl mx-auto px-6 py-12 w-full">
    <h2 class="text-3xl font-bold text-slate-800 mb-8">Můj Profil</h2>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg mb-6"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-sakura-light flex flex-col md:flex-row gap-8 items-start">
        
        <div class="w-full md:w-1/3 flex flex-col items-center">
            <div class="w-40 h-40 rounded-full border-4 border-sakura-light overflow-hidden shadow-md mb-4 bg-slate-100 shrink-0">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-sakura text-white text-5xl font-bold">
                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                Role: <?= htmlspecialchars($user['role'] ?? 'user') ?>
            </span>
        </div>

        <div class="w-full md:w-2/3">
            <div class="mb-6 space-y-2 text-slate-700 text-lg">
                <p><strong class="text-slate-900">Uživatelské jméno:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong class="text-slate-900">E-mail:</strong> <?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <h3 class="font-bold text-slate-800 mb-4">Změnit avatar</h3>
                <form action="<?= BASE_URL ?>/index.php?url=user/profile" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="file" name="avatar" accept="image/jpeg, image/png, image/webp, image/gif" required
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-sakura-light file:text-sakura-dark hover:file:bg-sakura hover:file:text-white transition-all cursor-pointer">
                    
                    <button type="submit" class="px-6 py-2 bg-sakura text-white font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-all">
                        Nahrát obrázek
                    </button>
                </form>
            </div>
        </div>

    </div>
    <div class="mt-12 bg-white p-8 rounded-xl shadow-sm border border-sakura-light">
        <h3 class="text-2xl font-extrabold text-slate-800 mb-8 border-b border-slate-100 pb-3 flex items-center gap-2">
            <span class="text-sakura">🌸</span> Můj osobní Dere-list
        </h3>

        <?php if (empty($animeList)): ?>
            <div class="text-center py-8 text-slate-500 bg-slate-50 rounded-lg border border-slate-200">
                <p>Tvůj seznam je zatím prázdný.</p>
                <a href="<?= BASE_URL ?>/index.php" class="text-sakura font-bold hover:underline mt-2 inline-block">Prohlédni si anime a něco přidej!</a>
            </div>
        <?php else: ?>
            <?php 
                // Mapa pro názvy a barvy stavů
                $statusMap = [
                    'watching' => ['Název' => 'Sleduji', 'Barva' => 'bg-green-100 text-green-700 border-green-200', 'Ikona' => '👀'],
                    'completed' => ['Název' => 'Dokončeno', 'Barva' => 'bg-blue-100 text-blue-700 border-blue-200', 'Ikona' => '✅'],
                    'plan_to_watch' => ['Název' => 'Chci vidět', 'Barva' => 'bg-slate-100 text-slate-700 border-slate-200', 'Ikona' => '📝'],
                    'on_hold' => ['Název' => 'Pozastaveno', 'Barva' => 'bg-amber-100 text-amber-700 border-amber-200', 'Ikona' => '⏸️'],
                    'dropped' => ['Název' => 'Dropnuto', 'Barva' => 'bg-red-100 text-red-700 border-red-200', 'Ikona' => '🚮']
                ];
                
                // Roztřídíme anime podle stavu
                $categorizedAnime = [];
                foreach ($animeList as $entry) {
                    $categorizedAnime[$entry['status']][] = $entry;
                }
            ?>

            <div class="space-y-10">
                <?php foreach ($statusMap as $statusCode => $statusInfo): ?>
                    <?php if (isset($categorizedAnime[$statusCode])): ?>
                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 shadow-inner">
                            <h4 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                                <span><?= $statusInfo['Ikona'] ?></span>
                                <?= $statusInfo['Název'] ?>
                                <span class="ml-2 text-xs font-black px-2 py-0.5 rounded-full <?= $statusInfo['Barva'] ?> border shadow-sm">
                                    <?= count($categorizedAnime[$statusCode]) ?>
                                </span>
                            </h4>

                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
                                <?php foreach ($categorizedAnime[$statusCode] as $anime): ?>
                                    
                                    <div class="group relative aspect-[3/4] bg-white rounded-lg shadow-sm border border-sakura-light overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg">
                                        
                                        <a href="<?= BASE_URL ?>/index.php?url=anime/detail/<?= htmlspecialchars($anime['anime_id']) ?>" class="block w-full h-full relative overflow-hidden">
                                            <?php if (!empty($anime['image'])): ?>
                                                <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($anime['image']) ?>" 
                                                     alt="<?= htmlspecialchars($anime['primary_title']) ?>" 
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            <?php else: ?>
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400 p-2 text-center text-xs">
                                                    [Bez obrázku]
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($anime['score'])): ?>
                                                <span class="absolute top-1.5 left-1.5 bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded shadow-sm z-10 flex items-center gap-0.5">
                                                    ⭐ <?= $anime['score'] ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <span class="absolute top-1.5 right-1.5 bg-slate-900/80 backdrop-blur-sm text-white text-[9px] uppercase tracking-wider font-bold px-1.5 py-0.5 rounded shadow-sm z-10">
                                                <?= htmlspecialchars($anime['episodes'] ?? '?') ?> ep
                                            </span>

                                            <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-slate-950/90 to-transparent z-10">
                                                <h5 class="font-bold text-[13px] leading-tight text-white line-clamp-2" title="<?= htmlspecialchars($anime['primary_title']) ?>">
                                                    <?= htmlspecialchars($anime['primary_title']) ?>
                                                </h5>
                                            </div>
                                        </a>

                                    </div>
                                    
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>