<?php

namespace App\Controllers;

use App\Models\MahasiswaModel as Mahasiswa;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class MahasiswaController extends ResourceController
{
    protected $format = 'json';
    protected $myValiddation;
    public function __construct()
    {
        $this->myValiddation = \Config\Services::validation();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $mahasiswa = new Mahasiswa();
        $data = $mahasiswa->getMahasiswa();
        $response = [
            'count' => $mahasiswa->countAllResults(),
            'data' => $data
        ];
        return $this->respond($response, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $mahasiswa = new Mahasiswa();
        $data = $mahasiswa->getMahasiswa($id);
        if ($data) {
            return $this->respond($data, 200);
        } else {
            return $this->failNotFound('Mahasiswa tidak di temukan dengan id ' . $id);
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $mahasiswa = new Mahasiswa();
        $rules = [
            'fullname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                ],
            ],
            'nim' => [
                'rules' => 'required|is_unique[mahasiswa.nim]',
                'errors' => [
                    'required' => 'NIM harus diisi',
                    'is_unique' => 'NIM sudah terdaftar',
                ],
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[mahasiswa.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                    'is_unique' => 'Email sudah terdaftar',
                ],
            ],
            'user_image' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Foto harus diisi',
                ],
            ]
        ];
        if ($this->validate($rules)) {
            $data = [
                'fullname' => $this->request->getVar('fullname'),
                'nim' => $this->request->getVar('nim'),
                'email' => $this->request->getVar('email'),
                'user_image' => $this->request->getVar('user_image'),
            ];
            $mahasiswa->insert($data);
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'Data berhasil ditambahkan'
                ]
            ];
            return $this->respondCreated($response);
        } else {
            $response = [
                'status' => 400,
                'error' => $this->validator->getErrors(),
                'messages' => [
                    'error' => 'Data gagal ditambahkan'
                ]
            ];
            return $this->respond($response);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $mahasiswa = new Mahasiswa();
        $rules = [
            'fullname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                ],
            ],
            'nim' => [
                'rules' => 'required|is_unique[mahasiswa.nim,id,' . $id . ']',
                'errors' => [
                    'required' => 'NIM harus diisi',
                    'is_unique' => 'NIM sudah terdaftar',
                ],
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[mahasiswa.email,id,' . $id . ']',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                    'is_unique' => 'Email sudah terdaftar',
                ],
            ],
            'user_image' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Foto harus diisi',
                ],
            ]
        ];
        if ($this->validate($rules)) {
            $data = [
                'fullname' => $this->request->getVar('fullname'),
                'nim' => $this->request->getVar('nim'),
                'email' => $this->request->getVar('email'),
                'user_image' => $this->request->getVar('user_image'),
            ];
            $mahasiswa->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data berhasil diubah'
                ]
            ];
            return $this->respond($response);
        } else {
            $response = [
                'status' => 400,
                'error' => $this->validator->getErrors(),
                'messages' => [
                    'error' => 'Data gagal diubah'
                ]
            ];
            return $this->respond($response);
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $mahasiswa = new Mahasiswa();
        $data = $mahasiswa->find($id);
        if ($data) {
            $mahasiswa->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data berhasil dihapus'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Mahasiswa tidak di temukan dengan id ' . $id);
        }
    }
}
