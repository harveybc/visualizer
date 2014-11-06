<?php
//para leer el param get y con el reconfigurar los dropdown
$sufix = "";
if (isset($_GET['id'])) {
    $modeloId = Motores::model()->findByAttributes(array("id" => $_GET['id']));
    if (isset($modeloId))
    $sufix = "?id=" . urlencode($modeloId->TAG);
}
?>
<style type="text/css">

div.loading {
    background-color: #FFFFFF;
    background-image: url('/images/loading.gif');
    background-position:  100px;
    background-repeat: no-repeat;
    opacity: 1;
}
div.loading * {
    opacity: .8;
}

    .select_admin{

        background:#ffffff;        
        border: 1px solid #DBC08F;
        -moz-border-radius:3px;
        -webkit-border-radius: 2px;
        border-radius:2px;


    }

    .back{


        padding-left:0px


    }




</style> 


<script type="text/javascript">
    // función que actualiza el campo de Area dependiendo del campo de proceso
    function updateFieldArea()
    {
<?php
echo CHtml::ajax(array(
    'type' => 'GET', //request type
    'data' => array('proceso' => 'js:document.getElementById("proceso").value'),
    'url' => CController::createUrl('/motores/dynamicArea'), //url to call.
    //'update' => '#Visitas_idDoctor', //selector to update
    'success' => 'updateAreaDropdown',
    'beforeSend' => 'function(){
      $("#myDiv").addClass("loading");}',
    'complete' => 'function(){
      $("#myDiv").removeClass("loading");}',
    'dataType' => 'json')
);
?>
        //document.getElementById('Examenes_convenio').selectedIndex = conv;
        return false;
    }
    
    function updateFieldEquipo()
    {
<?php
echo CHtml::ajax(array(
    'type' => 'GET', //request type
    'data' => array('area' => 'js:document.getElementById("area").value'),
    'url' => CController::createUrl('/motores/dynamicEquipo'), //url to call.
    //'update' => '#Visitas_idDoctor', //selector to update
    'success' => 'updateEquipoDropdown',
    'beforeSend' => 'function(){
      $("#myDiv").addClass("loading");}',
    'complete' => 'function(){
      $("#myDiv").removeClass("loading");}',
    'dataType' => 'json')
);
?>
        //document.getElementById('Examenes_convenio').selectedIndex = conv;
        return false;
    }

    function updateFieldMotor()
    {
<?php
echo CHtml::ajax(array(
    'type' => 'GET', //request type
    'data' => array('equipo' => 'js:document.getElementById("equipo").value'),
    'url' => CController::createUrl('/motores/dynamicFMotor'), //url to call.
    //'update' => '#Visitas_idDoctor', //selector to update
    'success' => 'updateMotorDropdown',
     'beforeSend' => 'function(){
      $("#myDiv").addClass("loading");}',
    'complete' => 'function(){
      $("#myDiv").removeClass("loading");}',
    'dataType' => 'json')
);
?>
        //document.getElementById('Examenes_convenio').selectedIndex = conv;
        return false;
    }

    function updateGridFechas()
    {
        $('#linkNuevo').html('<a href="/index.php/vibraciones/create?id='+encodeURIComponent(document.getElementById("motor").value)+'">Nueva medición</a>');
<?php
echo CHtml::ajax(array(
    'type' => 'GET', //request type
    'data' => array('TAG' => 'js:document.getElementById("motor").value'),
    'update' => '#gridFechas',
    'url' => CController::createUrl('/vibraciones/dynamicFechas'), //url to call.
        //'update' => '#Visitas_idDoctor', //selector to update
        //'success' => 'updateContGridMotores',
        //'dataType' => 'json'
        )
);
?>
        //document.getElementById('Examenes_convenio').selectedIndex = conv;
        return false;
    }

    // función que cambia los datos de el Dropdownlist de Area
    function updateAreaDropdown(data)
    {
        $('#area').html(data.value1);
        updateFieldEquipo();
    };

    // función que cambia los datos de el Dropdownlist de Equipo
    function updateEquipoDropdown(data)
    {
        $('#equipo').html(data.value1);
        updateFieldMotor();
    };

    // función que cambia los datos de el Dropdownlist de Equipo
    function updateMotorDropdown(data)
    {
        $('#motor').html(data.value1);
        $('#gridFechas').html("");
    };


</script>


<?php
$this->breadcrumbs = array(
    'Vibraciones' => array(Yii::t('app', 'index')),
    Yii::t('app', 'Gestionar'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Instrucciones'), 'url' => array('/Archivos/displayArchivo?id=25')),
    array('label' => Yii::t('app', 'Lista de Registros'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Nuevo Registro'),
        'url' => array('create' . $sufix),
        'itemOptions' => array('id' => 'linkNuevo')),
);

Yii::app()->clientScript->registerScript('search', "
			$('.search-button').click(function(){
				$('.search-form').toggle();
				return false;
				});
			$('.search-form form').submit(function(){
				$.fn.yiiGridView.update('vibraciones-grid', {
data: $(this).serialize()
});
				return false;
				});
			");
?>
<?php $this->setPageTitle (' Gestionar&nbsp; registro de Vibraciones y Temperatura'); ?>
<div name="myDiv" id="myDiv" class="forms100cb forms50cl">
    <?php
        /* PAra inicializar motores con nuevo plan de mantenimiento.
         * FALTA: asignar planmaint antes de guardar en create
    
        // TODO: quitar SOLO PARA ARRGEGLAR COSA DE planes de maint.
        // lee todos los modelos
        $modelos=Motores::model()->findAll();
        // para cada modelo, asigna a plan_mant_vibraciones,plan_mant_aislamiento,plan_mant_lubricantes,plan_mant_ultrasonido,plan_mant_termografia
        $count=0;
        foreach ($modelos as $modelo) {
            $modelo->plan_mant_vibraciones=$modelo->id+11000000;
            $modelo->plan_mant_aislamiento=$modelo->id+12000000;
            $modelo->plan_mant_lubricantes=$modelo->id+13000000;
            $modelo->plan_mant_ultrasonido=$modelo->id+14000000;
            $modelo->plan_mant_termografia=$modelo->id+15000000;
            $modelo->save();
            $count++;
        }
        echo "<br/>motores:".$count."<br/>";
        // lee todos los modelos
        $modelos=Tableros::model()->findAll();
        // para cada modelo, asigna a plan_mant_vibraciones,plan_mant_aislamiento,plan_mant_lubricantes,plan_mant_ultrasonido,plan_mant_termografia
        $count=0;
        foreach ($modelos as $modelo) {
            $modelo->plan_mant_termografia=$modelo->id+21000000;
            $modelo->save();
            $count++;
        }
        echo "<br/>tableros:".$count."<br/>";        
        // lee todos los modelos
        $modelos=Estructura::model()->findAll();
        // para cada modelo, asigna a plan_mant_vibraciones,plan_mant_aislamiento,plan_mant_lubricantes,plan_mant_ultrasonido,plan_mant_termografia
        $count=0;
        foreach ($modelos as $modelo) {
            $modelo->plan_mant_ultrasonido=$modelo->id+31000000;
            $modelo->save();
            $count++;
        }
        echo "<br/>equipos:".$count."<br/>";   
        
        */
    ?>
    <form>
        <table style="width:100%; ">
            <tr>
                <td style="width:50%;">
                    <b>Area:</b>
                    <?php
// si existe el parámetro Id, configura los preseleccionados
                    $valor = isset($modeloId) ? $modeloId->Proceso : "";
// dibuja el dropDownList de Proceso, seleccionando los valores diferentes presentes en la tabla Estructura col. Proceso
                    echo CHtml::dropDownList(
                            'proceso', $valor, CHtml::listData(Estructura::model()->findAllbySql(
                                            'SELECT DISTINCT Proceso FROM estructura ORDER BY Proceso ASC', array()), 'Proceso', 'Proceso'
                            ), array(
                        //'onfocus' => 'updateFieldArea()',
                        'onchange' => 'updateFieldArea()',
                        'style' => 'width:100%;',
                        'class' => 'select'
                            )
                    );
                    ?>
                    <!-- an la app original era:SELECT DISTINCT Area , Indicativo FROM Estructura WHERE (Proceso=@Proceso) ORDER BY Indicativo ASC -->
                </td>
                <td style="width:50%;">
                    <b>Proceso:</b>
                    <?php
// si existe el parámetro Id, configura los preseleccionados
                    $valor = isset($modeloId) ? $modeloId->Area : "";
// dibuja el dropDownList de Area, dependiendo del proceso selecccionado
                    echo CHtml::dropDownList(
                            'area', $valor, CHtml::listData(Estructura::model()->findAllbySql(
                                            isset($modeloId) ?
                                                    'SELECT DISTINCT Area FROM estructura WHERE Proceso="' . $modeloId->Proceso . '" ORDER BY Area ASC' : 'SELECT DISTINCT Area FROM estructura WHERE Proceso="ELABORACION" ORDER BY Area ASC', array()), 'Area', 'Area'
                            ), array(
                        //'onfocus' => 'updateFieldArea()',
                        'onchange' => 'updateFieldEquipo()',
                        'style' => 'width:100%;',
                        'class' => 'select',
                        'empty' => 'Seleccione el proceso',
                            )
                    );
                    ?>
                </td>
            </tr>
        </table>


        <table style="width:100%; ">
            <tr>
                <td>
                    <b>Equipo:<br/></b>
                    <?php
// si existe el parámetro Id, configura los preseleccionados
                    $valor = isset($modeloId) ? $modeloId->Equipo : "";
// dibuja el dropDownList de Proceso, seleccionando los valores diferentes presentes en la tabla Estructura col. Proceso
                    echo CHtml::dropDownList(
                            'equipo', $valor, CHtml::listData(Estructura::model()->findAllbySql(
                                            isset($modeloId) ?
                                                    'SELECT Equipo FROM estructura WHERE Area="' . $modeloId->Area . '" ORDER BY Equipo ASC' : 'SELECT Equipo FROM estructura WHERE Area="FILTRACION" ORDER BY Equipo ASC', array()), 'Equipo', 'Equipo'
                            ), array(
                        //'onfocus' => 'updateFieldEquipo()',
                        'onchange' => 'updateFieldMotor()',
                        'style' => 'width:100%;',
                        'class' => 'select',
                        'empty' => 'Seleccione el equipo',
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Motor:<br/></b>
                    <?php
// si existe el parámetro Id, configura los preseleccionados
                    $valor = isset($modeloId) ? $modeloId->TAG : "";
// dibuja el dropDownList de Motores, 
                    echo CHtml::dropDownList(
                            'motor', $valor, CHtml::listData(Motores::model()->findAllbySql(
                                            isset($modeloId) ?
                                                    'SELECT TAG, CONCAT(TAG," - ",Motor) as Motor FROM motores WHERE Equipo="' . $modeloId->Equipo . '" ORDER BY TAG ASC' : 'SELECT TAG, CONCAT(TAG," - ",Motor) as Motor FROM motores WHERE Equipo="ANILLO DE CONTRAPRESION" ORDER BY TAG ASC', array()), 'TAG', 'Motor'
                            ), array(
                        //'onfocus' => 'updateFieldEquipo()',
                        'onchange' => 'updateGridFechas()',
                        'style' => 'width:100%;',
                        'class' => 'select',
                        'empty' => 'Seleccione el motor para filtrar el resultado',
                            )
                    );
                    ?>
                </td>
            </tr>
        </table>
    </form>
</div>



<div id="gridFechas" name="gridFechas">  
    <!--       <legend>Datos Personales</legend> --->
    <?php
    if (isset($modeloId)) {
        echo "<script language=javascript>updateGridFechas()</script>";
    } else {

        /*
          $this->widget('zii.widgets.grid.CGridView', array(
          'id' => 'clinicas-grid',
          'dataProvider' => $model->search(),
          //  'filter' => $model,
          'cssFile' => '/themes/gridview/styles.css',     'template'=> '{items}{pager}{summary}',     'summaryText'=>'Resultados del {start} al {end} de {count} encontrados',
          'columns' => array(
          'Fecha',
          'TAG',
          'OT',
          array(
          'class' => 'CButtonColumn',
          ),
          ),
          ));
         */
    }
    ?>
   
</div>