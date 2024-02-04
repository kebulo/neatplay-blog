<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo $__env->yieldContent('title'); ?> -
        <?php echo e(config('app.name')); ?>
    </title>
    <?php echo view('site.partials.styles'); ?>
</head>

<body>
    <?php echo view('site.partials.header'); ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo view('site.partials.footer'); ?>
    <?php echo view('site.partials.scripts'); ?>
</body>

</html>