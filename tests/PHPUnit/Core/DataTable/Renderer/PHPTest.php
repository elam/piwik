<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 */
class DataTable_Renderer_PHPTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        Piwik_DataTable_Manager::getInstance()->deleteAll();
    }

    /**
     * DATA TESTS
     * -----------------------
     * for each renderer we test the case
     * - datatableSimple
     * - normal datatable  with 2 row (including columns and metadata)
     */
    protected function _getDataTableTest()
    {
        $dataTable = new Piwik_DataTable();
        
        $arraySubTableForRow2 = array (
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'sub1', 'count' => 1, 'bool' => false) ),
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'sub2', 'count' => 2, 'bool' => true) ),
        );
        $subDataTableForRow2 = new Piwik_DataTable();
        $subDataTableForRow2->addRowsFromArray($arraySubTableForRow2);

        $array = array (
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'Google&copy;', 'bool' => false, 'goals' => array('idgoal=1' => array('revenue'=> 5.5, 'nb_conversions' => 10)), 'nb_uniq_visitors' => 11, 'nb_visits' => 11, 'nb_actions' => 17, 'max_actions' => '5', 'sum_visit_length' => 517, 'bounce_count' => 9),
                        Piwik_DataTable_Row::METADATA => array('url' => 'http://www.google.com/display"and,properly', 'logo' => './plugins/Referers/images/searchEngines/www.google.com.png'),
                     ),
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'Yahoo!', 'nb_uniq_visitors' => 15, 'bool' => true, 'nb_visits' => 151, 'nb_actions' => 147, 'max_actions' => '50', 'sum_visit_length' => 517, 'bounce_count' => 90),
                        Piwik_DataTable_Row::METADATA => array('url' => 'http://www.yahoo.com', 'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png'),
                        Piwik_DataTable_Row::DATATABLE_ASSOCIATED => $subDataTableForRow2,
                     )
            );
        $dataTable->addRowsFromArray($array);
        return $dataTable;
    }

    protected function _getDataTableSimpleTest()
    {
        $array = array ( 'max_actions' => 14.0, 'nb_uniq_visitors' => 57.0, 'nb_visits' => 66.0, 'nb_actions' => 151.0, 'sum_visit_length' => 5118.0, 'bounce_count' => 44.0, );

        $table = new Piwik_DataTable_Simple;
        $table->addRowsFromArray($array);
        return $table;
    }

    protected function _getDataTableSimpleOneRowTest()
    {
        $array = array ( 'nb_visits' => 14.0 );

        $table = new Piwik_DataTable_Simple;
        $table->addRowsFromArray($array);
        return $table;
    }

    protected function _getDataTableEmpty()
    {
        $table = new Piwik_DataTable;
        return $table;
    }

    protected function _getDataTableSimpleOneZeroRowTest()
    {
        $array = array ( 'nb_visits' => 0 );
        $table = new Piwik_DataTable_Simple;
        $table->addRowsFromArray($array);
        return $table;
    }

    protected function _getDataTableSimpleOneFalseRowTest()
    {
        $array = array ( 'is_excluded' => false );
        $table = new Piwik_DataTable_Simple;
        $table->addRowsFromArray($array);
        return $table;
    }


    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPTest1()
    {
        $dataTable = $this->_getDataTableTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $render->setRenderSubTables(true);

        $expected = serialize(array (
                      0 =>
                      array (
                        'label' => 'Google&copy;',
                        'bool' => false,
                        'goals' => array(
                            'idgoal=1' => array(
                                'revenue' => 5.5,
                                'nb_conversions' => 10,
                            ),
                        ),
                        'nb_uniq_visitors' => 11,
                        'nb_visits' => 11,
                        'nb_actions' => 17,
                        'max_actions' => '5',
                        'sum_visit_length' => 517,
                        'bounce_count' => 9,
                        'url' => 'http://www.google.com/display"and,properly',
                        'logo' => './plugins/Referers/images/searchEngines/www.google.com.png',
                      ),
                      1 =>
                      array (
                        'label' => 'Yahoo!',
                        'nb_uniq_visitors' => 15,
                        'bool' => true,
                        'nb_visits' => 151,
                        'nb_actions' => 147,
                        'max_actions' => '50',
                        'sum_visit_length' => 517,
                        'bounce_count' => 90,
                        'url' => 'http://www.yahoo.com',
                        'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png',
                          'idsubdatatable' => 1,
                        'subtable' =>
                            array (
                              0 =>
                              array (
                                'label' => 'sub1',
                                'count' => 1,
                                'bool' => false,
                              ),
                              1 =>
                              array (
                                'label' => 'sub2',
                                'count' => 2,
                                'bool' => true,
                              ),
                        ),
                      ),
                    ));
        $rendered = $render->render(null);
        $this->assertEquals($expected, $rendered);
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPTest2()
    {
        $dataTable = $this->_getDataTableSimpleTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $expected = serialize(array (
                  'max_actions' => 14.0,
                  'nb_uniq_visitors' => 57.0,
                  'nb_visits' => 66.0,
                  'nb_actions' => 151.0,
                  'sum_visit_length' => 5118.0,
                  'bounce_count' => 44.0,
                ));
        $this->assertEquals($expected, $render->render());
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPTest3()
    {
        $dataTable = $this->_getDataTableSimpleOneRowTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $expected = serialize(14.0);
        $this->assertEquals($expected, $render->render());
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPTest4()
    {
        $dataTable = $this->_getDataTableEmpty();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $expected = serialize(array());
        $this->assertEquals($expected, $render->render());
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPTest5()
    {
        $dataTable = $this->_getDataTableSimpleOneZeroRowTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $expected = serialize(0);
        $this->assertEquals($expected, $render->render());
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPTest6()
    {
        $dataTable = $this->_getDataTableSimpleOneFalseRowTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $expected = serialize(false);
        $this->assertEquals($expected, $render->render());
    }


    /**
     * DATA OF DATATABLE_ARRAY
     * -------------------------
     */

    protected function _getDataTableArrayTest()
    {
        $array1 = array (
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'Google', 'nb_uniq_visitors' => 11, 'nb_visits' => 11, ),
                        Piwik_DataTable_Row::METADATA => array('url' => 'http://www.google.com', 'logo' => './plugins/Referers/images/searchEngines/www.google.com.png'),
                     ),
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'Yahoo!', 'nb_uniq_visitors' => 15, 'nb_visits' => 151, ),
                        Piwik_DataTable_Row::METADATA => array('url' => 'http://www.yahoo.com', 'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png'),
                     )
            );
        $table1 = new Piwik_DataTable();
        $table1->addRowsFromArray($array1);


        $array2 = array (
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'Google1&copy;', 'nb_uniq_visitors' => 110, 'nb_visits' => 110,),
                        Piwik_DataTable_Row::METADATA => array('url' => 'http://www.google.com1', 'logo' => './plugins/Referers/images/searchEngines/www.google.com.png1'),
                     ),
            array ( Piwik_DataTable_Row::COLUMNS => array( 'label' => 'Yahoo!1', 'nb_uniq_visitors' => 150, 'nb_visits' => 1510,),
                        Piwik_DataTable_Row::METADATA => array('url' => 'http://www.yahoo.com1', 'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png1'),
                     )
            );
        $table2 = new Piwik_DataTable();
        $table2->addRowsFromArray($array2);

        $table3 = new Piwik_DataTable();


        $table = new Piwik_DataTable_Array();
        $table->setKeyName('testKey');
        $table->addTable($table1, 'date1');
        $table->addTable($table2, 'date2');
        $table->addTable($table3, 'date3');

        return $table;
    }

    protected function _getDataTableSimpleArrayTest()
    {
        $array1 = array ( 'max_actions' => 14.0, 'nb_uniq_visitors' => 57.0,  );
        $table1 = new Piwik_DataTable_Simple;
        $table1->addRowsFromArray($array1);

        $array2 = array ( 'max_actions' => 140.0, 'nb_uniq_visitors' => 570.0,  );
        $table2 = new Piwik_DataTable_Simple;
        $table2->addRowsFromArray($array2);

        $table3 = new Piwik_DataTable_Simple;

        $table = new Piwik_DataTable_Array();
        $table->setKeyName('testKey');
        $table->addTable($table1, 'row1');
        $table->addTable($table2, 'row2');
        $table->addTable($table3, 'row3');

        return $table;
    }

    protected function _getDataTableSimpleOneRowArrayTest()
    {
        $array1 = array ( 'nb_visits' => 14.0 );
        $table1 = new Piwik_DataTable_Simple;
        $table1->addRowsFromArray($array1);
        $array2 = array ( 'nb_visits' => 15.0 );
        $table2 = new Piwik_DataTable_Simple;
        $table2->addRowsFromArray($array2);

        $table3 = new Piwik_DataTable_Simple;

        $table = new Piwik_DataTable_Array();
        $table->setKeyName('testKey');
        $table->addTable($table1, 'row1');
        $table->addTable($table2, 'row2');
        $table->addTable($table3, 'row3');

        return $table;
    }

    protected function _getDataTableArray_containsDataTableArray_normal()
    {
        $table = new Piwik_DataTable_Array();
        $table->setKeyName('parentArrayKey');
        $table->addTable($this->_getDataTableArrayTest(), 'idSite');
        return $table;
    }

    protected function _getDataTableArray_containsDataTableArray_simple()
    {
        $table = new Piwik_DataTable_Array();
        $table->setKeyName('parentArrayKey');
        $table->addTable($this->_getDataTableSimpleArrayTest(), 'idSite');
        return $table;
    }

    protected function _getDataTableArray_containsDataTableArray_simpleOneRow()
    {
        $table = new Piwik_DataTable_Array();
        $table->setKeyName('parentArrayKey');
        $table->addTable($this->_getDataTableSimpleOneRowArrayTest(), 'idSite');
        return $table;
    }


    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPArrayTest1()
    {
        $dataTable = $this->_getDataTableArrayTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $rendered = $render->render();

        $expected = serialize(array (
                  'date1' =>
                  array (
                    0 =>
                    array (
                      'label' => 'Google',
                      'nb_uniq_visitors' => 11,
                      'nb_visits' => 11,
                      'url' => 'http://www.google.com',
                      'logo' => './plugins/Referers/images/searchEngines/www.google.com.png',
                    ),
                    1 =>
                    array (
                      'label' => 'Yahoo!',
                      'nb_uniq_visitors' => 15,
                      'nb_visits' => 151,
                      'url' => 'http://www.yahoo.com',
                      'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png',
                    ),
                  ),
                  'date2' =>
                  array (
                    0 =>
                    array (
                      'label' => 'Google1&copy;',
                      'nb_uniq_visitors' => 110,
                      'nb_visits' => 110,
                      'url' => 'http://www.google.com1',
                      'logo' => './plugins/Referers/images/searchEngines/www.google.com.png1',
                    ),
                    1 =>
                    array (
                      'label' => 'Yahoo!1',
                      'nb_uniq_visitors' => 150,
                      'nb_visits' => 1510,
                      'url' => 'http://www.yahoo.com1',
                      'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png1',
                    ),
                ),
                  'date3' => array (),
                  ));
        $this->assertEquals($expected, $rendered);
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPArrayTest2()
    {
        $dataTable = $this->_getDataTableSimpleArrayTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $rendered = $render->render();

        $expected = serialize(array (
              'row1' =>
              array (
                'max_actions' => 14.0,
                'nb_uniq_visitors' => 57.0,
              ),
              'row2' =>
              array (
                'max_actions' => 140.0,
                'nb_uniq_visitors' => 570.0,
              ),
              'row3' =>
              array (
              ),
            ));
        $this->assertEquals($expected, $rendered);
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPArrayTest3()
    {
        $dataTable = $this->_getDataTableSimpleOneRowArrayTest();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $rendered = $render->render();

        $expected = serialize(array (
                  'row1' => 14.0,
                  'row2' => 15.0,
                  'row3' => array(),
                ));
        $this->assertEquals($expected, $rendered);
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPArrayIsMadeOfArrayTest1()
    {
        $dataTable = $this->_getDataTableArray_containsDataTableArray_normal();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $rendered = $render->render();

        $expected = serialize(array('idSite'=>
            array (
                  'date1' =>
                  array (
                    0 =>
                    array (
                      'label' => 'Google',
                      'nb_uniq_visitors' => 11,
                      'nb_visits' => 11,
                      'url' => 'http://www.google.com',
                      'logo' => './plugins/Referers/images/searchEngines/www.google.com.png',
                    ),
                    1 =>
                    array (
                      'label' => 'Yahoo!',
                      'nb_uniq_visitors' => 15,
                      'nb_visits' => 151,
                      'url' => 'http://www.yahoo.com',
                      'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png',
                    ),
                  ),
                  'date2' =>
                  array (
                    0 =>
                    array (
                      'label' => 'Google1&copy;',
                      'nb_uniq_visitors' => 110,
                      'nb_visits' => 110,
                      'url' => 'http://www.google.com1',
                      'logo' => './plugins/Referers/images/searchEngines/www.google.com.png1',
                    ),
                    1 =>
                    array (
                      'label' => 'Yahoo!1',
                      'nb_uniq_visitors' => 150,
                      'nb_visits' => 1510,
                      'url' => 'http://www.yahoo.com1',
                      'logo' => './plugins/Referers/images/searchEngines/www.yahoo.com.png1',
                    ),
                ),
                  'date3' => array (),
                  )));

        $this->assertEquals($expected, $rendered);
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPArrayIsMadeOfArrayTest2()
    {
        $dataTable = $this->_getDataTableArray_containsDataTableArray_simple();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $rendered = $render->render();

        $expected = serialize(array ('idSite'=>
            array(
              'row1' =>
              array (
                'max_actions' => 14.0,
                'nb_uniq_visitors' => 57.0,
              ),
              'row2' =>
              array (
                'max_actions' => 140.0,
                'nb_uniq_visitors' => 570.0,
              ),
              'row3' =>
              array (
              ),
            )));
        $this->assertEquals($expected, $rendered);
    }

    /**
     * @group Core
     * @group DataTable
     * @group DataTable_Renderer
     * @group DataTable_Renderer_PHP
     */
    public function testPHPArrayIsMadeOfArrayTest3()
    {
        $dataTable = $this->_getDataTableArray_containsDataTableArray_simpleOneRow();
        $render = new Piwik_DataTable_Renderer_Php();
        $render->setTable($dataTable);
        $rendered = $render->render();

        $expected = serialize(array ('idSite'=>
            array(
                  'row1' => 14.0,
                  'row2' => 15.0,
                  'row3' => array(),
                )));
        $this->assertEquals($expected, $rendered);
    }
}
