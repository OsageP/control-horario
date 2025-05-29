<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyManager extends Component
{
    public $companies;
    public $name, $email, $address, $company_id;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->loadCompanies();
    }

    public function loadCompanies()
    {
        $this->companies = Company::all();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->address = '';
        $this->company_id = null;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        Company::create([
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Empresa creada exitosamente.');

        $this->resetInputFields();
        $this->loadCompanies();
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        $this->company_id = $company->id;
        $this->name = $company->name;
        $this->email = $company->email;
        $this->address = $company->address;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        $company = Company::findOrFail($this->company_id);
        $company->update([
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Empresa actualizada exitosamente.');

        $this->resetInputFields();
        $this->loadCompanies();
    }

    public function delete($id)
    {
        Company::findOrFail($id)->delete();
        session()->flash('message', 'Empresa eliminada exitosamente.');
        $this->loadCompanies();
    }

    public function render()
    {
        return view('livewire.company-manager');
    }
}
