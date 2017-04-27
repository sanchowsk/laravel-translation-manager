@extends('backend.index')

@section('content')

    <div style="width: 80%; margin: auto;">
        <h1>@lang('backend.menu_translations')</h1>

        <div class="alert alert-success success-publish" style="display:none;">
            <p>@lang('backend.done_save') '<?= $group ?>'!</p>
        </div>
        <?php if(Session::has('successPublish')) : ?>
        <div class="alert alert-info">
            <?php echo Session::get('successPublish'); ?>
        </div>
        <?php endif; ?>
        <p>
        <?php if(isset($group)) : ?>
        <form class="form-inline form-publish" method="POST"
              action="<?= action('\Barryvdh\TranslationManager\Controller@postPublish', $group) ?>" data-remote="true"
              role="form"
              data-confirm="@lang('backend.save_translation1') '<?= $group ?>? @lang('backend.save_translation2')">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <button type="submit" class="btn btn-primary" data-disable-with="Publishing.."><span
                        class="glyphicon glyphicon-floppy-save"></span> @lang('backend.publish_translate')</button>
        </form>
        <?php endif; ?>
        </p>
        <div class="row">
            <div class="col-md-4">
                <form role="form">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="form-group">
                        <select name="group" id="group" class="form-control group-select js_group_translate">
                            <?php foreach($groups as $key => $value): ?>
                            <option value="<?= $key ?>"<?= $key == $group ? ' selected' : '' ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
                <?php if($group): ?>
                <form action="<?= action('\Barryvdh\TranslationManager\Controller@postAdd', array($group)) ?>"
                      method="POST" role="form">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <textarea class="form-control" rows="3" name="keys"
                              placeholder="@lang('backend.key_placeholder')"></textarea>
                    <p></p>
                    <button type="submit" class="btn btn-success"><span
                                class="glyphicon glyphicon-plus"></span> @lang('backend.add_keys')</button>
                </form>
            </div>
        </div>
        <hr>
        <h4>@lang('backend.total'): <?= $numTranslations ?>, @lang('backend.changed'): <?= $numChanged ?></h4>
        <table class="table">
            <thead>
            <tr>
                <th width="15%">@lang('backend.key')</th>
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
                    <a href="#edit" class="editable status-<?= $t ? $t->status : 0 ?> locale-<?= $locale ?>"
                       data-locale="<?= $locale ?>" data-name="<?= $locale . "|" . $key ?>" id="username"
                       data-type="textarea" data-pk="<?= $t ? $t->id : 0 ?>" data-url="<?= $editUrl ?>"
                       data-title="@lang('backend.enter_translation')"><?= $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' ?></a>
                </td>
                <?php endforeach; ?>
                <?php if($deleteEnabled): ?>
                <td>
                    <a href="<?= action('\Barryvdh\TranslationManager\Controller@postDelete', [$group, $key]) ?>"
                       class="delete-key btn btn-xs btn-danger"
                       data-confirm="@lang('backend.delete_translation') '<?= $key ?>?"><span
                                class="glyphicon glyphicon-trash"></span> </a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <?php else: ?>
        <p>@lang('backend.choose_group')</p>

        <?php endif; ?>
    </div>


@endsection
