<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Livewire\WithPagination;

#[\Livewire\Attributes\Title('Manajemen Pegawai')]
class EmployeeManager extends Component
{
    use WithPagination;

    public $employee_id, $name, $email, $password, $role = 'admin';
    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->employee_id,
            'password' => $this->employee_id ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required|in:admin,cashier',
        ];
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['employee_id', 'name', 'email', 'password', 'role']);
        $this->role = 'admin'; // default set
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['employee_id', 'name', 'email', 'password', 'role']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'business_name' => Auth::user()->business_name,
            'business_category' => Auth::user()->business_category ?? 'Umum',
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        User::updateOrCreate(['id' => $this->employee_id], $data);

        $this->closeModal();
        session()->flash('message', $this->employee_id ? 'Akun pegawai berhasil diperbarui.' : 'Akun pegawai berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $user = User::where('business_name', Auth::user()->business_name)->findOrFail($id);
        $this->employee_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // Don't retrieve password
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        if (Auth::id() == $id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }
        User::where('business_name', Auth::user()->business_name)->findOrFail($id)->delete();
        session()->flash('message', 'Pegawai berhasil dihapus.');
    }

    public function exportPdf()
    {
        return redirect()->route('pegawai.export');
    }

    public function render()
    {
        $businessName = Auth::user()->business_name;
        $totalStaff = User::where('business_name', $businessName)->count();
        $totalAdmins = User::where('business_name', $businessName)->where('role', 'admin')->count();
        $totalCashiers = User::where('business_name', $businessName)->where('role', 'cashier')->count();
        
        $employees = User::where('business_name', $businessName)->latest()->paginate(10);

        return view('livewire.employee-manager', compact('employees', 'totalStaff', 'totalAdmins', 'totalCashiers'));
    }
}
