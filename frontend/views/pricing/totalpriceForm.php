<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\TotalpriceForm $formModel */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = 'Totale prijs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-totalprice">
  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    Voor de totaal prijs, vul minimaal het kenteken in, de datum is dan van vandaag.
  </p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'totalprice-form']); ?>
        <?= $form->field($formModel, 'carLicense')->textInput(['autofocus' => true]) ?>

        <?= $form->field($formModel, 'startDate')->widget(DatePicker::classname(),          [
            'options' => [
              'placeholder' => 'Geef startdatum...',
              'class' => 'dtpinput'
              //'convertFormat' => true,
            ],
            'pluginOptions' => [
              'autoclose' => true,
              'calendarWeeks' => true,
              'todayBtn' => true,
              //'minuteStep' => 10, // assets/js/bootstrap-datetimepicker.js
              'format' => 'yyyy-mm-dd', //'dd-M-yyyy',
            ],
          ]
          );
        ?>

        <div class="form-group">
          <?= Html::submitButton('Vraag op', ['class' => 'btn btn-primary', 'name' => 'totalprice-button']) ?>
        </div>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
 
  <div class="row">
    <div class="col-lg-5">
      <h2>Totaal prijs:</h2>

      <?php if (!empty($totalprice)): ?>}
      <table border="0">
        <tr>
          <th>Prijs ex btw:</th>
          <td><?= $totalprice['totalExVat'] ?></td>
        </tr><tr>
          <th>BTW (<?= $totalprice['vatPercentage'] ?>%):</th>
          <td><?= $totalprice['vat'] ?></td>
        </tr><tr>
          <th>Totaal prijs:</th>
          <td><?= $totalprice['totalExVat'] + $totalprice['vat'] ?></td>
        </tr><tr>
          <th>Service uren:</th>
          <td><?= $totalprice['servicehours'] ?></td>
        </tr>
      </table>
      <?php else: ?>
        <p>Geen resultaat; vul het formulier in.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
