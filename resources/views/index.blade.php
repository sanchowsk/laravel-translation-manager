@extends('backend.index')

@section('content')

<div style="width: 80%; margin: auto;">
    <h1>Translation</h1>

    <div class="alert alert-success success-import" style="display:none;">
        <p>Done importing, processed <strong class="counter">N</strong> items! Reload this page to refresh the groups!</p>
    </div>
    <div class="alert alert-success success-find" style="display:none;">
        <p>Done searching for translations, found <strong class="counter">N</strong> items!</p>
    </div>
    <div class="alert alert-success success-publish" style="display:none;">
        <p>Done publishing the translations for group '<?= $group ?>'!</p>
    </div>
    <?php if(Session::has('successPublish')) : ?>
        <div class="alert alert-info">
            <?php echo Session::get('successPublish'); ?>
        </div>
    <?php endif; ?>
    <p>
        <?php if(isset($group)) : ?>
            <form class="form-inline form-publish" method="POST" action="<?= action('\Barryvdh\TranslationManager\Controller@postPublish', $group) ?>" data-remote="true" role="form" data-confirm="Are you sure you want to publish the translations group '<?= $group ?>? This will overwrite existing language files.">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <button type="submit" class="btn btn-info" data-disable-with="Publishing.." >Publish translations</button>
                <a href="<?= action('\Barryvdh\TranslationManager\Controller@getIndex') ?>" class="btn btn-default">Back</a>
            </form>
        <?php endif; ?>
    </p>
    <form role="form">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="form-group">
            <select name="group" id="group" class="form-control group-select">
                <?php foreach($groups as $key => $value): ?>
                    <option value="<?= $key ?>"<?= $key == $group ? ' selected':'' ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <?php if($group): ?>
        <form action="<?= action('\Barryvdh\TranslationManager\Controller@postAdd', array($group)) ?>" method="POST"  role="form">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <textarea class="form-control" rows="3" name="keys" placeholder="Add 1 key per line, without the group prefix"></textarea>
            <p></p>
            <input type="submit" value="Add keys" class="btn btn-primary">
        </form>
        <hr>
    <h4>Total: <?= $numTranslations ?>, changed: <?= $numChanged ?></h4>
    <table class="table">
        <thead>
        <tr>
            <th width="15%">Key</th>
            <?php foreach($locales as $locale): ?>
                <th><?= $locale ?></th>
            <?php endforeach; ?>
            <?php if($deleteEnabled): ?>
                <th>&nbsp;</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>

        <?php foreach($translations as $key => $translation): ?>
            <tr id="<?= $key ?>">
                <td><?= $key ?></td>
                <?php foreach($locales as $locale): ?>
                    <?php $t = isset($translation[$locale]) ? $translation[$locale] : null?>

                    <td>
                        <a href="#edit" class="editable status-<?= $t ? $t->status : 0 ?> locale-<?= $locale ?>" data-locale="<?= $locale ?>" data-name="<?= $locale . "|" . $key ?>" id="username" data-type="textarea" data-pk="<?= $t ? $t->id : 0 ?>" data-url="<?= $editUrl ?>" data-title="Enter translation"><?= $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' ?></a>
                    </td>
                <?php endforeach; ?>
                <?php if($deleteEnabled): ?>
                    <td>
                        <a href="<?= action('\Barryvdh\TranslationManager\Controller@postDelete', [$group, $key]) ?>" class="delete-key" data-confirm="Are you sure you want to delete the translations for '<?= $key ?>?"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    <?php else: ?>
        <p>Choose a group to display the group translations. If no groups are visible, make sure you have run the migrations and imported the translations.</p>

    <?php endif; ?>
</div>


@endsection
