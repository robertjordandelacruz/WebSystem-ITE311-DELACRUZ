<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function new()
    {
        helper(['form']);
        return view('register');
    }

    public function create()
{
    helper(['form']);
    $users = new UserModel();

    $data = [
        'name'     => $this->request->getPost('name'),
        'email'        => $this->request->getPost('email'),
        'password'     => $this->request->getPost('password'),
        'pass_confirm' => $this->request->getPost('pass_confirm'),
        'role'         => 'user',
    ];

    if (! $users->save($data)) {
            // redirect back with input + validation errors
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $users->errors());
        }

        return redirect()->to('/register/success')
                         ->with('success', 'Account created!');
}

    public function success()
    {
        return view('register_success');
    }
    public function index()
    {
        helper(['form', 'url']);
        return view('login');
    }
     public function auth()
    {
        $session = session();
        $users   = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $users->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'id'       => $user['id'],
                    'name' => $user['name'],
                    'email'    => $user['email'],
                    'role'     => $user['role'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Wrong password.');
            }
        } else {
            return redirect()->back()->with('error', 'Email not found.');
        }
    }

    public function register()
    {
        helper('form');
        $data = [];

        if ($this->request->is('post')) {
            $validationRules = [
                'name'            => 'required|min_length[3]|max_length[50]',
                'email'           => 'required|valid_email|is_unique[users.email]',
                'password'        => 'required|min_length[8]|max_length[255]',
                'password_confirm'=> 'matches[password]',
            ];

            if ($this->validate($validationRules)) {
                $db = \Config\Database::connect();
                $builder = $db->table('users');

                $userRecord = [
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role'       => 'student',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if ($builder->insert($userRecord)) {
                    return redirect()->to(site_url('login'))
                                     ->with('success', 'Your account has been created. Please login.');
                }

                $data['error'] = 'Unable to save user data. Try again later.';
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('auth/register', $data);
    }

    /**
     * Display login form and authenticate user
     */
    public function login()
    {
        helper('form');
        $data = [];

        if ($this->request->is('post')) {
            $loginRules = [
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[8]|max_length[255]',
            ];

            if ($this->validate($loginRules)) {
                $email    = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                $db = \Config\Database::connect();
                $user = $db->table('users')->where('email', $email)->get()->getRow();

                if ($user && password_verify($password, $user->password)) {
                    $session = session();
                    $session->set([
                        'uid'       => $user->id,
                        'fullname'  => $user->name,
                        'email'     => $user->email,
                        'role'      => $user->role,
                        'logged_in' => true,
                    ]);

                    return redirect()->to(site_url('dashboard'));
                }

                $data['error'] = 'Invalid email or password.';
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('auth/login', $data);
    }

    /**
     * Dashboard page (protected route)
     */
    public function dashboard()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'))
                             ->with('error', 'Please log in to access the dashboard.');
        }

        return view('auth/dashboard');
    }

    /**
     * Logout and destroy session
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
