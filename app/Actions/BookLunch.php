<?php
/**
 * Created by PhpStorm.
 * Filename: BookLunch.php
 * User: Nguyễn Văn Ước
 * Date: 28/09/2021
 * Time: 09:15
 */

namespace App\Actions;

use App\Models\Staff;
use TCG\Voyager\Actions\AbstractAction;

class BookLunch extends AbstractAction
{
    public function getTitle()
    {
        return 'Đặt cơm';
    }

    public function getIcon()
    {
        return 'voyager-rocket';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-dark pull-right btn-book',
            'style' => 'margin-right: 5px;',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.staff.book', $this->data->{$this->data->getKeyName()});
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'staff';
    }

    public function shouldActionDisplayOnRow($row)
    {
        return $row->isBookToday() != Staff::statusBook['BOOK_SUCCESS'];
    }
}
