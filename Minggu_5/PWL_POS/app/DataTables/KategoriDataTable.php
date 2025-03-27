<?php

namespace App\DataTables;

use App\Models\KategoriModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KategoriDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // ->addColumn('action', 'kategori.action')
            ->addColumn('action', function ($row) {
            $editUrl = route('editKategori', $row->kategori_id);
            $deleteUrl = route('deleteKategori', $row->kategori_id);
            $csrfToken = csrf_token();

            return '
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                            Aksi
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="'.$editUrl.'">Edit</a>
                            <form action="'.$deleteUrl.'" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus?\')" class="dropdown-item">
                                <input type="hidden" name="_token" value="'.$csrfToken.'">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-link text-danger p-0 m-0 border-0">Hapus</button>
                            </form>
                        </div>
                    </div>
                </td>
            ';
        })
        ->rawColumns(['action'])
        ->setRowId('kategori_id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(KategoriModel $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kategori-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('kategori_id')->title('ID'),
            Column::make('kategori_kode')->title('Kode'),
            Column::make('kategori_nama')->title('Nama'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->title('Aksi'), // Menambahkan judul kolom
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Kategori_' . date('YmdHis');
    }
}