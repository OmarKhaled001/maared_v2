
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable" data-theme="default" data-bs-theme="light" data-topbar="light">
<title>الرئيسية</title>
<meta content="Minimal Admin & dashboard Template" name="description">
    @include('main.meta')
</head>
<body>
    <div id="layout-wrapper">
        @include('main.topbar')
        @include('main.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                          
                            <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-md-2 row-cols-1">
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate border-primary">
                                        <div class="card-body">
                                            <span class="badge bg-success-subtle text-success float-end">{{ $statistics['volunteersMasaol_count'] + $statistics['volunteersMashroaaMasaol_count']}}</span>
                                            <h4 class="mb-4"><span class="counter-value" data-target="{{ $statistics['volunteersMasaol_count'] + $statistics['volunteersMashroaaMasaol_count']}}">0</span></h4>
                            
                                            <p class="text-muted fw-medium text-uppercase mb-0">اجمالي فريق العمل</p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                          
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate {{$statistics['volunteersMasaolContribution_count'] < $statistics['volunteersMasaol_count']  ? 'border-danger' : 'border-success'}}">
                                        <div class="card-body">
                                            <span class="badge {{$statistics['volunteersMasaolContribution_count'] < $statistics['volunteersMasaol_count'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'}} float-end">{{ $statistics['volunteersMasaolContribution_count'] }} / {{$statistics['volunteersMasaol_count']}}</span>
                                            <h4 class="mb-4"><span class="counter-value" data-target="{{$statistics['averagemasaolContribution']}}">0</span> /8</h4>
                                            <p class="text-muted fw-medium text-uppercase mb-0">متوسط مشاركات مسؤول</p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate {{$statistics['volunteersMashroaaMasaolContribution_count'] < $statistics['volunteersMashroaaMasaol_count']  ? 'border-danger' : 'border-success'}}">
                                        <div class="card-body">
                                            <span class="badge {{$statistics['volunteersMashroaaMasaolContribution_count'] < $statistics['volunteersMashroaaMasaol_count'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'}} float-end">{{ $statistics['volunteersMashroaaMasaolContribution_count'] }} / {{$statistics['volunteersMashroaaMasaol_count']}}</span>
                                            <h4 class="mb-4"><span class="counter-value" data-target="{{$statistics['averagemashroaaMasaolContribution']}}">0</span> /8</h4>
                                            <p class="text-muted fw-medium text-uppercase mb-0">متوسط مشاركات مشروع مسؤول</p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                              
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate border-warning">
                                        <div class="card-body">
                                            <span class="badge bg-success-subtle text-success float-end">{{$statistics['events_count']}}</span>

                                            <h4 class="mb-4"><span class="counter-value" data-target="{{$statistics['events_count']}}">{{$statistics['events_count']}}</span></h4>
                            
                                            <p class="text-muted fw-medium text-uppercase mb-0">اجمالي  الاحداث </p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                               
                            
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate border-warning">
                                        <div class="card-body">
                                            <h4 class="mb-4"><span class="counter-value" data-target="{{$statistics['new_volunteers_count']}}">0</span></h4>
                            
                                            <p class="text-muted fw-medium text-uppercase mb-0">اجمالي المتطوعين  الجدد</p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate border-warning">
                                        <div class="card-body">
                                            <h4 class="mb-4"><span class="counter-value" data-target="{{$statistics['total_volunteers_count']}}">0</span></h4>
                            
                                            <p class="text-muted fw-medium text-uppercase mb-0">اجمالي المتطوعين  بالتكرار</p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col">
                                    <div class="card border-bottom border-3 card-animate border-warning">
                                        <div class="card-body">
                                            <h4 class="mb-4"><span class="counter-value" data-target="{{$statistics['unique_volunteers_count']}}">0</span></h4>
                            
                                            <p class="text-muted fw-medium text-uppercase mb-0">اجمالي المتطوعين بدون تكرار</p>
                                        </div>
                                    </div>
                                </div><!--end col-->
                           
                        </div>
                    
                    </div>
                </div>
            </div>
            @include('main.footer')
        </div>
    </div>
    @include('main.scripts')
</body>
</html>
