<?php

use App\Core\Controller;
use App\Core\DB;
use App\Core\Redirect;
use App\Core\Session;

class Rekening extends Controller
{

    protected $db;

    public function __construct()
    {
        App\Core\Authentication::auth('admin');
        $this->db = new DB;
    }

    public function index()
    {
        $data['judul'] = 'Rekening Bank';
        $data['rekening'] = $this->db
            ->select('*')
            ->from('rekening_bank')
            ->whereIsNull('deleted_at')
            ->get();

        $this->view('admin/templates/header', $data);
        $this->view('admin/rekening/index', $data);
        $this->view('admin/templates/footer');
    }

    public function create()
    {
        $data['judul'] = 'Tambah Rekening Bank';

        $this->view('admin/templates/header', $data);
        $this->view('admin/rekening/tambah');
        $this->view('admin/templates/footer');
    }

    public function store()
    {
        if ($this->db->insert('rekening_bank', [
            'id' => null,
            'nama' => $_POST['nama_bank'],
            'atas_nama' => $_POST['atas_nama'],
            'nomor' => $_POST['no_rekening'],
            'slug' => textToSlug($_POST['no_rekening'] . '' . date('yds')),
            'created_at' => currentTimeStamp(),
            'updated_at' => currentTimeStamp(),
            'deleted_at' => null
        ])) {
            Session::setFlash('Rekening Baru Berhasil Ditambahkan', 'success');
        } else {
            Session::setFlash('Rekening Baru Gagal Ditambahkan', 'danger');
        }
        Redirect::to('admin/rekening');
    }

    public function edit($slug)
    {
        $data['judul'] = 'Edit Rekening Bank';
        $data['rekening'] = $this->db
            ->select('*')
            ->from('rekening_bank')
            ->whereIsNull('deleted_at')
            ->andWhere('slug', '=', $slug)
            ->first();
        if ($data['rekening']) {
            $this->view('admin/templates/header', $data);
            $this->view('admin/rekening/edit', $data);
            $this->view('admin/templates/footer');
        } else {
            Redirect::error('404', 'admin');
        }
    }

    public function update($id)
    {
        if ($this->db->update('rekening_bank', [
            'nama' => $_POST['nama_bank'],
            'atas_nama' => $_POST['atas_nama'],
            'nomor' => $_POST['no_rekening'],
            'slug' => textToSlug($_POST['no_rekening'] . '' . date('yds')),
            'updated_at' =>  currentTimeStamp()
        ], 'id', '=', $id)) {
            Session::setFlash('Rekening Berhasil Diupdate', 'success');
        } else {
            Session::setFlash('Rekening Gagal Diupdate', 'danger');
        }
        Redirect::to('admin/rekening');
    }

    public function destroy()
    {
        if ($this->db
            ->update(
                'rekening_bank',
                ['deleted_at' => currentTimeStamp()],
                'id',
                '=',
                $_POST['id']
            )
        ) {
            Session::setFlash('Rekening Berhasil Dihapus', 'success');
        } else {
            Session::setFlash('Rekening Gagal Dihapus', 'danger');
        }
        Redirect::to('admin/rekening');
    }
}
