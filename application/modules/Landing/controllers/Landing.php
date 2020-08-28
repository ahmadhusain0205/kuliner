<?php
class Landing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        $data['judul'] = 'Landing Page';
        $this->load->view('Templates/Header_landing', $data);
        $this->load->view('V_landing', $data);
        $this->load->view('Templates/Footer_landing');
    }

    function join()
    {
        //validasi nama
        $this->form_validation->set_rules('name', 'Name', 'required|trim', [
            'required' => 'Nama harus diisi'
        ]);
        //validasi email
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'Email sudah terdaftar',
            'required' => 'Email harus diisi'
        ]);
        //validasi alamat
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim', [
            'required' => 'Alamat harus diisi'
        ]);
        //validasi password
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[4]|matches[password2]', [
            'matches' => 'Password tidak sama !',
            'min_length' => 'Password terlalu pendek !',
            'required' => 'Password harus diisi'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
        //validasi gagal
        if ($this->form_validation->run() == false) {
            redirect('Landing');
        }
        //validasi sukses
        else {
            //masukan data
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'alamat' => htmlspecialchars($this->input->post('alamat', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'id_role' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];
            //kirim data ke database dengan table user
            $this->db->insert('user', $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Selamat!</strong> Akun anda sudah terdaftar.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('Landing');
        }
    }
    function login_aksi()
    {
        //validasi email
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'Email sudah terdaftar',
            'required' => 'Email harus diisi'
        ]);
        //validasi password
        $this->form_validation->set_rules('password', 'Password', 'required|trim', [
            'required' => 'Password harus diisi'
        ]);
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        //jika user ada
        if ($user != null) {
            //jika user aktif
            if ($user['is_active'] == 1) {
                //cek password jika benar
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'id_role' => $user['id_role']
                    ];
                    //simpan data ke session
                    $this->session->set_userdata($data);
                    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Selamat!</strong> Anda berhasil login.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>');
                    redirect('Dashboard');
                }
                //jika salah
                else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>gagal Login!</strong> Password yang anda masukan salah.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>');
                    redirect('Landing');
                }
            }
            //jika user tidak aktif
            else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal Login!</strong> Email belum di aktivasi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
                redirect('Landing');
            }
        }
        //jika tidak ada
        else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal Login!</strong> Email belum terdaftar.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
            redirect('Landing');
        }
    }
}
