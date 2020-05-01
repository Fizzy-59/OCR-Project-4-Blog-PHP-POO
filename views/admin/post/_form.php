
<form action="" method="POST" enctype="multipart/form-data">
    <?php echo $form->input('name', 'Titre'); ?>
    <?php echo $form->input('slug', 'URL'); ?>

    <div class="row">
        <div class="col-md-8">
             <?php echo $form->file('image', 'Image à la Une'); ?>
        </div>
        <div class="col-mb-4">
            <!-- Display image if existing on db -->
            <?php if ($post->getImage()): ?>
                <img src="<?php echo $post->getImageURL('small'); ?>" alt="" style="width: 100%">
            <?php endif; ?>
        </div>
    </div>

    <?php echo $form->select('categories_ids', 'Catégories', $categories); ?>
    <?php echo $form->textarea('content', 'Contenu')?>
    <?php echo $form->input('created_at', 'Date de création'); ?>

    <button class="btn btn-primary">
        <?php if ($post->getId() !== null) : ?>
            Modifier
        <?php else: ?>
            Créer
        <?php endif; ?>
    </button>
</form>
