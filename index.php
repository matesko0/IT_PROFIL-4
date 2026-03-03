<?php
$message = "";
$messageType = "";

$jsonData = file_exists('profile.json') ? file_get_contents('profile.json') : '[]';
$interests = json_decode($jsonData, true) ?? [];

if (isset($_POST["new_interest"])) {
    $newInterest = trim($_POST["new_interest"]);

    if (empty($newInterest)) {
        $message = "Pole nesmí být prázdné.";
        $messageType = "error";
    } else {
        $loweredInterests = array_map('strtolower', $interests);
        
        if (in_array(strtolower($newInterest), $loweredInterests)) {
            $message = "Tento zájem už existuje.";
            $messageType = "error";
        } else {
            $interests[] = $newInterest;
            file_put_contents('profile.json', json_encode($interests, JSON_UNESCAPED_UNICODE));
            $message = "Zájem byl úspěšně přidán.";
            $messageType = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Profil 4.0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background: linear-gradient(-45deg, #1e1b4b, #312e81, #1e3a8a, #1e1b4b);
            background-size: 400% 400%;
            animation: gradient 25s ease infinite;
        }
    </style>
</head>
<body class="animate-gradient min-h-screen flex items-center justify-center p-6 font-sans text-white">

    <div class="bg-black/30 backdrop-blur-2xl border border-white/10 p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md">
        <header class="text-center mb-10">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Můj Profil</h1>
            <p class="text-white/40 text-xs uppercase tracking-[0.3em]">Skill Storage</p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-2xl text-sm font-medium border <?php echo $messageType === 'success' ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' : 'bg-rose-500/10 border-rose-500/30 text-rose-300'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <input type="text" name="new_interest" required
                placeholder="Co se učíš?" 
                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-blue-500/50 transition-all placeholder:text-white/20 text-white">
            
            <button type="submit"
                class="w-full bg-white text-blue-950 hover:bg-blue-50 py-4 rounded-2xl font-bold shadow-lg transform hover:-translate-y-1 active:scale-95 transition-all">
                Uložit zájem
            </button>
        </form>

        <div class="mt-12">
            <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-white/30 mb-6 text-center">Moje zájmy</h3>
            <div class="flex flex-wrap gap-3 justify-center">
                <?php if (empty($interests)): ?>
                    <p class="text-white/10 italic text-sm">Zatím žádná data...</p>
                <?php else: ?>
                    <?php foreach ($interests as $interest): ?>
                        <span class="bg-white/5 border border-white/5 px-5 py-2.5 rounded-2xl text-sm font-light tracking-wide transition-all hover:bg-white/10">
                            <?php echo htmlspecialchars($interest); ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
