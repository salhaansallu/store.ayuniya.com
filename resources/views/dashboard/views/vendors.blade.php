@extends('dashboard.views.layouts.app')

@section('dashboard')

<style>
  form .input {
    margin-top: 0;
  }
</style>

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Manufacturers</span></div>
    <div class="create"><button class="secondary_btn" type="button" onclick="location.href='/web-admin/vendor-register';"><i class="fa-solid fa-plus"></i> Add new</button></div>
</div>

<div class="products">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td>Company</td>
                    <td>Company email</td>
                    <td>Company No.</td>
                    <td>Business type</td>
                </tr>
            </thead>

            <tbody>
              @foreach ($vendors as $vendor)
              <tr>
                <td><p>{{ $vendor->company_name }}({{ $vendor->store_name }})</p></td>
                <td>{{ $vendor->company_email }}</td>
                <td>{{ $vendor->company_number }}</td>
                <td>{{ $vendor->business_type }}</td>
            </tr>
              @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
      @isset ($products)
      @if ($products->links()->paginator->hasPages())
          {{ $products->appends(request()->query())->links() }}
      @endif
      @endisset
    </div>
</div>

@endsection
