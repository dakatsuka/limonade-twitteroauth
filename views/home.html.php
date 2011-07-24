<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Twitter OAuth Sample (Limonade)</title>
    </head>
    <body>
        <h1>Twitter OAuth Sample (Limonade)</h1>
        <p><a href="signout">Sign out</a></p>

        <?php foreach($timeline as $status) { ?>
            <div>
                <dl>
                    <dt><?= $status->user->screen_name ?> (<?= $status->user->name ?>)</dt>
                    <dd><?= htmlspecialchars($status->text) ?></dd>
                    <dd><?= $status->created_at ?> by <?= $status->source ?></dd>
                </dl>
            </div>
        <?php } ?>
    </body>
</html>
