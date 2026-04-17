<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_OFF);

function escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function normalizeNullable(?string $value): ?string
{
    $value = trim((string) $value);

    if ($value === '' || strtoupper($value) === 'NULL') {
        return null;
    }

    return $value;
}

function formatHumanDate(string $date): string
{
    $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', $date);
    return $dateTime ? $dateTime->format('d/m/Y') : $date;
}

function formatDurationLabel(string $durationIso): string
{
    if (preg_match('/^P(\d+)D$/', $durationIso, $matches)) {
        $days = (int) $matches[1];
        return $days . ' jour' . ($days > 1 ? 's' : '');
    }

    return $durationIso;
}

function integerOrNull(mixed $value): ?int
{
    if ($value === null || $value === '') {
        return null;
    }

    return (int) $value;
}

$dbHost = getenv('GLAM_DB_HOST') ?: '127.0.0.1';
$dbUser = getenv('GLAM_DB_USER') ?: 'root';
$dbPassword = getenv('GLAM_DB_PASSWORD') ?: 'root';
$dbName = getenv('GLAM_DB_NAME') ?: 'GLAM_FESTIVAL';
$dbPort = (int) (getenv('GLAM_DB_PORT') ?: '3307');

$connectionError = null;
$festivals = [];

$mysqli = @new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

if ($mysqli->connect_error) {
    $connectionError = $mysqli->connect_error;
} else {
    $mysqli->set_charset('utf8mb4');
    $sql = <<<SQL
        SELECT idFestival, edition_year, name, slug, description, start_date, end_date,
               duration_iso, location_name, location_city, location_country, organizer_name,
               funder_name, keywords, maximum_attendee_capacity, in_language, image_path, official_url
        FROM Festivals
        ORDER BY start_date ASC, name ASC
    SQL;
    $result = $mysqli->query($sql);

    if (!$result) {
        $connectionError = $mysqli->error;
    } else {
        while ($row = $result->fetch_assoc()) {
            $festivals[] = $row;
        }
    }

    $mysqli->close();
}

$total = count($festivals);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivals culturels | Corpus GLAM Festival</title>
    <meta name="description" content="Projet PHP/MySQLi sémantique autour d'un corpus de festivals 2025-2026, publié avec les microdonnées schema.org/Festival.">
    <link rel="stylesheet" href="../css/festival.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero">
            <p class="eyebrow">CTM - M2</p>
            <h1>Festivals culturels</h1>
            <p class="lead">
                Plongez au cœur des événements culturels majeurs. Un catalogue dynamique
                et sémantiquement enrichi explorant les dates, lieux et spécificités des
                plus grands festivals internationaux.
            </p>
            <div class="hero-stats">
                <div class="stat-card">
                    <span class="stat-number"><?php echo $total; ?></span>
                    <span class="stat-label">festivals</span>
                </div>
                <?php if ($connectionError === null && $festivals !== []): ?>
                    <section class="index-card" aria-labelledby="festival-index-title">
                        <div class="index-heading">
                            <h2 id="festival-index-title">Accès rapide aux festivals</h2>
                            <p>Cliquez sur un festival pour atteindre sa fiche et la mettre en évidence.</p>
                        </div>
                        <nav class="quick-index" aria-label="Index rapide des festivals">
                            <?php foreach ($festivals as $festival): ?>
                                <?php $cardId = 'festival-' . $festival['slug']; ?>
                                <a href="#<?php echo escape($cardId); ?>" class="quick-index-link" data-festival-target="<?php echo escape($cardId); ?>">
                                    <?php echo escape($festival['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </section>
                <?php endif; ?>
            </div>
        </header>
        <main class="content">
            <?php if ($connectionError !== null): ?>
                <section class="notice notice-error">
                    <h2>Connexion MySQL indisponible</h2>
                    <p>Importez d'abord <code>m2-festival/SQL/festivals.sql</code> dans la base <code>GLAM_FESTIVAL</code> ou lancez le projet via Docker.</p>
                    <p class="technical-details"><?php echo escape($connectionError); ?></p>
                </section>
            <?php else: ?>
                <section class="notice">
                    <p>Nous avons collecté et répertorié les données essentielles pour chaque festival, incluant notamment le nom, les dates de début et de fin, la durée, le lieu, l'organisateur, le financeur, les mots-clés, la capacité maximale d'accueil, la langue utilisée ainsi que l'image illustrative.</p>
                </section>
                <section class="cards" aria-label="Corpus des festivals">
                    <?php foreach ($festivals as $festival): ?>
                        <?php
                        $capacity = integerOrNull($festival['maximum_attendee_capacity'] ?? null);
                        $imagePath = normalizeNullable($festival['image_path'] ?? null);
                        $officialUrl = normalizeNullable($festival['official_url'] ?? null);
                        $funder = normalizeNullable($festival['funder_name'] ?? null);
                        $organizer = normalizeNullable($festival['organizer_name'] ?? null);
                        $language = normalizeNullable($festival['in_language'] ?? null);
                        $cardId = 'festival-' . $festival['slug'];
                        ?>
                        <article
                            id="<?php echo escape($cardId); ?>"
                            class="festival-card"
                            itemscope
                            itemtype="https://schema.org/Festival"
                            itemid="<?php echo escape($officialUrl ?? $cardId); ?>"
                        >
                            <?php if ($imagePath !== null): ?>
                                <div class="card-media"><img src="<?php echo escape($imagePath); ?>" alt="Visuel de <?php echo escape($festival['name']); ?>" itemprop="image" loading="lazy"></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <p class="edition-badge">Edition <?php echo (int) $festival['edition_year']; ?></p>
                                <h2 itemprop="name"><?php echo escape($festival['name']); ?></h2>
                                <p class="description" itemprop="description"><?php echo escape($festival['description']); ?></p>
                                <div class="timeline">
                                    <span class="date-chip"><time itemprop="startDate" datetime="<?php echo escape($festival['start_date']); ?>"><?php echo escape(formatHumanDate($festival['start_date'])); ?></time></span>
                                    <span class="date-arrow">&rarr;</span>
                                    <span class="date-chip"><time itemprop="endDate" datetime="<?php echo escape($festival['end_date']); ?>"><?php echo escape(formatHumanDate($festival['end_date'])); ?></time></span>
                                    <span class="duration-chip"><meta itemprop="duration" content="<?php echo escape($festival['duration_iso']); ?>"><?php echo escape(formatDurationLabel($festival['duration_iso'])); ?></span>
                                </div>
                                <dl class="facts">
                                    <div>
                                        <dt>Lieu</dt>
                                        <dd itemprop="location" itemscope itemtype="https://schema.org/Place">
                                            <span itemprop="name"><?php echo escape($festival['location_name']); ?></span>
                                            <span class="subline"><span itemprop="addressLocality"><?php echo escape($festival['location_city']); ?></span>, <span itemprop="addressCountry"><?php echo escape($festival['location_country']); ?></span></span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt>Organisateur</dt>
                                        <dd>
                                            <?php if ($organizer !== null): ?>
                                                <span itemprop="organizer" itemscope itemtype="https://schema.org/Organization"><span itemprop="name"><?php echo escape($organizer); ?></span></span>
                                            <?php else: ?><span>Non renseigné</span><?php endif; ?>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt>Financeur</dt>
                                        <dd>
                                            <?php if ($funder !== null): ?>
                                                <span itemprop="funder" itemscope itemtype="https://schema.org/Organization"><span itemprop="name"><?php echo escape($funder); ?></span></span>
                                            <?php else: ?><span>Non renseigné</span><?php endif; ?>
                                        </dd>
                                    </div>
                                    <div><dt>Mots-clés</dt><dd itemprop="keywords"><?php echo escape($festival['keywords'] ?? ''); ?></dd></div>
                                    <div>
                                        <dt>Capacité max</dt>
                                        <dd><?php if ($capacity !== null): ?><span itemprop="maximumAttendeeCapacity"><?php echo escape(number_format($capacity, 0, ',', ' ')); ?></span><?php else: ?><span>Non renseignée</span><?php endif; ?></dd>
                                    </div>
                                    <div><dt>Langues</dt><dd itemprop="inLanguage"><?php echo escape($language ?? 'Non renseigné'); ?></dd></div>
                                </dl>
                                <div class="links"><?php if ($officialUrl !== null): ?><a href="<?php echo escape($officialUrl); ?>" itemprop="url">Site officiel</a><?php endif; ?></div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </main>
    </div>
    <script>
    (() => {
        const links = document.querySelectorAll('[data-festival-target]');
        let highlightTimer = null;
        let activeCard = null;

        const highlightCard = (card) => {
            if (activeCard !== null) {
                activeCard.classList.remove('is-highlighted');
            }

            if (highlightTimer !== null) {
                window.clearTimeout(highlightTimer);
            }

            activeCard = card;
            activeCard.classList.add('is-highlighted');
            highlightTimer = window.setTimeout(() => {
                activeCard?.classList.remove('is-highlighted');
                activeCard = null;
                highlightTimer = null;
            }, 5000);
        };

        links.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();

                const targetId = link.getAttribute('data-festival-target');
                if (!targetId) {
                    return;
                }

                const card = document.getElementById(targetId);
                if (!card) {
                    return;
                }

                card.scrollIntoView({ behavior: 'smooth', block: 'start' });

                try {
                    window.history.replaceState(null, '', '#' + targetId);
                } catch (error) {
                }

                highlightCard(card);
            });
        });
    })();
    </script>
</body>
</html>
