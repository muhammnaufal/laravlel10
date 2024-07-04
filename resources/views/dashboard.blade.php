@extends('layout.main')

@section('css')

@section('title', 'Dashboard')

<link href="{{ asset('assets/css/app.min.css') }}" id="app-stylesheet" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">


<style>
    .card {
        background-color: #191C24 !important;
    }
    .header-title {
        font-size: 1rem;
        margin: 0 0 7px 0;
    }
    .card-box {
        background-color: #191C24;
        padding: 1.5rem;
        -webkit-box-shadow: 0 0.75rem 6rem rgba(56,65,74,.03);
        box-shadow: 0 0.75rem 6rem rgba(56,65,74,.03);
        margin-bottom: 24px;
        border-radius: 0.25rem;
    }
    .font-weight-normal {
        font-weight: 400!important;
    }
    .pt-2, .py-2 {
        padding-top: 0.75rem!important;
    }

    .mb-1, .my-1 {
        margin-bottom: 0.375rem!important;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-white shadow-lg text-center rounded p-4">
            <div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <canvas id="myPieChart" style="" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
    </div>

    @if(session()->has('loginSuccess'))
        <script src="{{ asset('js/iziToast.min.js') }}"></script>
        <script>
            iziToast.show({
                title: 'Login Berhasil',
                message: "Selamat Datang Kembali {{ auth()->user()->name }}",
                position: 'topRight',
                color: 'green',
            });
        </script>
    @endif


@endsection



@section('js')
 <!-- Vendor js -->
 <script src="{{ asset('assets/js/vendor.min.js')}}"></script>

 <!-- knob plugin -->
 <script src="{{ asset('assets/libs/jquery-knob/jquery.knob.min.js')}}"></script>

 <!--Morris Chart-->
 <script src="{{ asset('assets/libs/morris-js/morris.min.js')}}"></script>
 <script src="{{ asset('assets/libs/raphael/raphael.min.js')}}"></script>

 <!-- Dashboard init js-->
 <script src="{{ asset('assets/js/pages/dashboard.init.js')}}"></script>

 <!-- App js -->
<script src="{{ asset('assets/js/app.min.js')}}"></script>

<script src="{{ asset('js/iziToast.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Ambil elemen canvas menggunakan ID
    var ctx = document.getElementById('myPieChart').getContext('2d');

    const chart2 = @json($bidang);

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function generateRandomColors(count) {
        var colors = [];
        for (var i = 0; i < count; i++) {
            colors.push(getRandomColor());
        }
        return colors;
    }

    var seriesCount = chart2.length;

    var randomColors = generateRandomColors(seriesCount);

    const labelNames = chart2.map(item => item.name);

    const count = chart2.map((item, index) => chart2[index].surat.length);

    // console.log(seriesCount);

    const data2 = {
        labels:labelNames,
        datasets: [{
            label: 'My First Dataset',
            data: count,
            backgroundColor: randomColors,
            borderColor: randomColors,
            borderWidth: 1
        }]
    };

    var data = {
        labels: labelNames,
        datasets: [{
            label: 'Jumlah Surat',
            data: count,
            backgroundColor: randomColors,
            hoverOffset: 4,
        }]
    };

    // Konfigurasi chart
    var options = {
        responsive: true,
        aspectRatio: 1.5,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Jumlah Surat di Setiap Bidang',
                font: {
                    size: 20,
                    weight: 'bold'
                }
            }
        }
    };


    // Buat chart pie
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });

</script>

@endsection

