<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-6xl mx-auto px-6 py-12 w-full flex flex-col md:flex-row gap-8 flex-1">
    
    <aside class="w-full md:w-1/3 lg:w-1/4 flex flex-col gap-6">
        <div class="bg-slate-200 w-full aspect-[3/4] rounded-xl shadow-sm border border-sakura-light flex items-center justify-center text-slate-400">
            <?php if (!empty($anime['image'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($anime['image']) ?>" 
                                 alt="<?= htmlspecialchars($anime['primary_title']) ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-xl">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-xs font-medium">Bez obrázku</span>
                            </div>
                        <?php endif; ?>
                        
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-sakura-light">
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mb-3">Můj Dere-list</h3>
                
                <form action="<?= BASE_URL ?>/index.php?url=anime/detail/<?= $anime['id'] ?>" method="POST" class="space-y-3">
                    <input type="hidden" name="action" value="update_list">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Stav</label>
                        <select name="status" id="status-select" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura text-sm font-medium transition-colors">
                            <option value="plan_to_watch" <?= (($myListEntry['status'] ?? '') == 'plan_to_watch') ? 'selected' : '' ?>>Chci vidět</option>
                            <option value="watching" <?= (($myListEntry['status'] ?? '') == 'watching') ? 'selected' : '' ?>>Sleduji</option>
                            <option value="completed" <?= (($myListEntry['status'] ?? '') == 'completed') ? 'selected' : '' ?>>Dokončeno</option>
                            <option value="on_hold" <?= (($myListEntry['status'] ?? '') == 'on_hold') ? 'selected' : '' ?>>Pozastaveno</option>
                            <option value="dropped" <?= (($myListEntry['status'] ?? '') == 'dropped') ? 'selected' : '' ?>>Dropnuto</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Hodnocení</label>
                        <select name="score" id="score-select" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura text-sm font-medium transition-colors">
                            <option value="">- Bez hodnocení -</option>
                            <?php for ($i = 10; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= (($myListEntry['score'] ?? '') == $i) ? 'selected' : '' ?>>
                                    <?= $i ?> / 10 <?php if($i==10) echo '(Mistrovské dílo)'; elseif($i==5) echo '(Průměrné)'; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2 mt-2 bg-sakura text-white font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-all text-sm">
                        <?= !empty($myListEntry) ? 'Upravit v seznamu' : 'Přidat do seznamu' ?>
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center text-sm text-slate-500">
                Pro přidání do seznamu se <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-sakura font-bold hover:underline">přihlas</a>.
            </div>
        <?php endif; ?>
        

        <div class="bg-white p-5 rounded-xl shadow-sm border border-sakura-light">
            <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mb-3">Informace</h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-slate-500 font-semibold block">Epizody:</span>
                    <span class="text-slate-800"><?= htmlspecialchars($anime['episodes'] ?? 'Neznámé') ?></span>
                </div>
                
                <?php if (!empty($titles['english'])): ?>
                <div>
                    <span class="text-slate-500 font-semibold block">Anglicky:</span>
                    <span class="text-slate-800"><?= htmlspecialchars($titles['english']) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($titles['japanese'])): ?>
                <div>
                    <span class="text-slate-500 font-semibold block">Japonsky:</span>
                    <span class="text-slate-800 font-serif"><?= htmlspecialchars($titles['japanese']) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($titles['romaji'])): ?>
                <div>
                    <span class="text-slate-500 font-semibold block">Romaji:</span>
                    <span class="text-slate-800"><?= htmlspecialchars($titles['romaji']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($anime['added_by_name'])): ?>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="text-xs font-medium text-slate-400 flex flex-col justify-center items-center gap-2">
                        <div>
                            Záznam vytvořil: <span class="font-bold text-slate-500"><?= htmlspecialchars($anime['added_by_name'] ?? 'Neznámý') ?></span> 
                            (<?= date('d. m. Y', strtotime($anime['created_at'])) ?>)
                        </div>
                        
                        <?php if (!empty($anime['updated_by_name'])): ?>
                        <div>
                            Naposledy upravil: <span class="font-bold text-slate-500"><?= htmlspecialchars($anime['updated_by_name']) ?></span>
                            <?php if (!empty($anime['updated_at'])): ?>
                                (<?= date('d. m. Y', strtotime($anime['updated_at'])) ?>)
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </aside>

    <section class="w-full md:w-2/3 lg:w-3/4">
        
       <div class="flex flex-col sm:flex-row justify-between items-start gap-6 mb-8">
            
            <div class="flex-1">
                <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-sakura transition-colors text-sm font-bold mb-2">
                    &larr; Zpět na seznam
                </a>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight leading-tight">
                    <?= htmlspecialchars($anime['primary_title']) ?>
                </h2>
            </div>
            
            <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl shadow-sm border border-sakura-light shrink-0">
                <div class="text-3xl drop-shadow-sm">⭐</div>
                <div>
                    <div class="text-3xl font-black text-slate-800 leading-none">
                        <?= !empty($anime['avg_score']) ? number_format($anime['avg_score'], 2) : 'N/A' ?>
                    </div>
                    <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-1">
                        <?= !empty($anime['total_votes']) ? $anime['total_votes'] . ' hodnocení' : 'Zatím bez hodnocení' ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-sakura-light">
            <h3 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-4">Synopse</h3>
            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($anime['description'] ?? 'Popis zatím nebyl přidán.') ?></p>
        </div>
        <div class="mt-6 bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-sakura-light">
            <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6 flex items-center gap-2">
                <span class="text-sakura text-lg">🖼️</span> Galerie a screenshoty
            </h3>
            

            <?php if (empty($gallery)): ?>
                <p class="text-slate-400 text-sm italic py-2">Pro toto anime zatím nebyly nahrány žádné obrázky do galerie.</p>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <?php foreach ($gallery as $img): ?>
                        
                        <div class="group relative aspect-[16/10] bg-slate-50 rounded-lg overflow-hidden border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                            <img src="<?= BASE_URL ?>/uploads/gallery/<?= htmlspecialchars($img) ?>" 
                                alt="Screenshot z anime" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-colors duration-300 pointer-events-none"></div>
                        </div>
                        
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="text-sakura">💬</span> Diskuze (<?= count($comments) ?>)
            </h3>

            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-8">
                <form action="<?= BASE_URL ?>/index.php?url=anime/detail/<?= $anime['id'] ?>" method="POST" class="flex gap-4">
                    
                    <div class="shrink-0">
                        <?php if (isset($currentUser) && !empty($currentUser['avatar'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($currentUser['avatar']) ?>" alt="Tvůj avatar" class="w-10 h-10 rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold">
                                <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="grow">
                        <textarea name="comment_content" required placeholder="Napiš svůj názor na tohle anime..." 
                                  class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura resize-none" rows="2"></textarea>
                        <div class="mt-3 text-right">
                            <button type="submit" class="px-6 py-2 bg-sakura text-white font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-colors">
                                Odeslat komentář
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-8 text-center">
                <p class="text-slate-500">Pro přidání komentáře se musíš <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-sakura font-bold hover:underline">přihlásit</a>.</p>
            </div>
        <?php endif; ?>

            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <p class="text-slate-500 text-center py-4">Zatím tu nejsou žádné komentáře. Buď první!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex gap-4">
                            
                            <div class="shrink-0">
                                <?php if (!empty($currentUser['avatar'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($currentUser['avatar']) ?>" alt="Tvůj avatar" class="w-10 h-10 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full bg-sakura text-white flex items-center justify-center font-bold">
                                        <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                                        
                            <div class="grow">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-800"><?= htmlspecialchars($comment['username']) ?></span>
                                        <span class="text-xs text-slate-400"><?= date('d. m. Y H:i', strtotime($comment['created_at'])) ?></span>
                                    </div>
                                    
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <div class="space-x-2 text-xs">
                                            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                                                <button onclick="toggleCommentEdit(<?= $comment['id'] ?>)" class="text-blue-500 hover:underline font-medium">Upravit</button>
                                            <?php endif; ?>
                                            
                                            <?php if ($comment['user_id'] == $_SESSION['user_id'] || ($_SESSION['user_role'] ?? '') === 'admin'): ?>
                                                <a href="<?= BASE_URL ?>/index.php?url=anime/deleteComment/<?= $comment['id'] ?>" onclick="return confirm('Opravdu smazat tento komentář?');" class="text-red-500 hover:underline font-medium">Smazat</a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div id="comment-text-<?= $comment['id'] ?>" class="text-slate-700 whitespace-pre-wrap text-sm"><?= htmlspecialchars($comment['content']) ?></div>

                                <?php if (isset($_SESSION['user_id']) && $comment['user_id'] == $_SESSION['user_id']): ?>
                                    <form id="comment-form-<?= $comment['id'] ?>" action="<?= BASE_URL ?>/index.php?url=anime/editComment/<?= $comment['id'] ?>" method="POST" class="hidden mt-2">
                                        <textarea name="content" rows="3" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura resize-none text-sm"><?= htmlspecialchars($comment['content']) ?></textarea>
                                        <div class="flex gap-2 mt-2">
                                            <button type="submit" class="px-4 py-1.5 bg-sakura text-white text-xs font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-colors">Uložit změny</button>
                                            <button type="button" onclick="toggleCommentEdit(<?= $comment['id'] ?>)" class="px-4 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg shadow-sm hover:bg-slate-200 transition-colors">Zrušit</button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

    </section>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status-select');
    const scoreSelect = document.getElementById('score-select');

    if (statusSelect && scoreSelect) {
        function toggleScore() {
            if (statusSelect.value === 'plan_to_watch') {
                scoreSelect.value = ""; // Vynuluje stávající hodnocení
                scoreSelect.disabled = true; // Zablokuje kliknutí
                scoreSelect.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            } else {
                scoreSelect.disabled = false; // Odblokuje kliknutí
                scoreSelect.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            }
        }

        // Spustit hned při načtení stránky (zkontroluje výchozí stav)
        toggleScore();

        // Poslouchat jakoukoliv změnu stavu uživatelem
        statusSelect.addEventListener('change', toggleScore);
    }
});
function toggleCommentEdit(commentId) {
    const textDiv = document.getElementById('comment-text-' + commentId);
    const formDiv = document.getElementById('comment-form-' + commentId);
    
    if (textDiv.classList.contains('hidden')) {
        // Zobrazit text, skrýt formulář
        textDiv.classList.remove('hidden');
        formDiv.classList.add('hidden');
    } else {
        // Skrýt text, zobrazit formulář
        textDiv.classList.add('hidden');
        formDiv.classList.remove('hidden');
    }
}
</script>
<?php require_once '../app/views/layout/footer.php'; ?>