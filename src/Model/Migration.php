<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Model;

use Phinx\Db\Table;
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Table\Column;

/**
 * Class Migration.
 */
abstract class Migration extends AbstractMigration
{
    public function change()
    {
        $table = $this->setUp();
        if (!$table->exists()) {

            $hasCreatedColumn = $hasUpdatedColumn = false;
            array_map(
                function (Column $column) use (&$hasCreatedColumn, &$hasUpdatedColumn) {
                    if ('created' === $column->getName()) {
                        $hasCreatedColumn = true;
                        return;
                    }
                    if ('updated' === $column->getName()) {
                        $hasUpdatedColumn = true;
                        return;
                    }
                },
                $table->getPendingColumns()
            );
            !$hasCreatedColumn && $table->addColumn('created', 'datetime');
            !$hasUpdatedColumn && $table->addColumn('updated', 'datetime');

            $table->create();
        }
        $this->dataSet($table);
    }

    /**
     * @return Table
     */
    abstract public function setUp();

    /**
     * @param Table $table
     *
     * @return mixed
     */
    abstract public function dataSet(Table $table);
}
