<?php
/**
 * Created by PhpStorm.
 * User: aleksandrsavelev
 * Date: 14.12.2018
 * Time: 1:51
 */

namespace core\tests\unit\board;


use core\forms\manage\Board\BoardManageForm;

class CreateBoardTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testSCorrectCreate()
    {
        $form = new BoardManageForm([

        ]);
    }
}