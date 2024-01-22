@extends('layouts.app')

@section('content')

    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeGSQDiLAAVaLziSCIYbMj5s4rHOguYZs=places"></script>
        <style>
            body {
                background: #eee;
            }

            .tab {
                display: none;
            }

            .navigate_btn {
                background-color: var(--primary--);
                color: #ffffff;
                border: none;
                padding: 10px 20px;
                font-size: 17px;
                font-family: Raleway;
                cursor: pointer;
            }

            button:hover {
                opacity: 0.8;
            }

            #prevBtn {
                background-color: #bbbbbb;
            }

            .step {

                margin: 0 2px;
                background-color: #bbbbbb;
                border: none;
                border-radius: 50%;
                display: inline-block;
                opacity: 0.5;
            }

            .step.active {
                opacity: 1;
            }

            .step.finish {
                background-color: #4CAF50;
            }

            .all-steps {
                text-align: center;
                ;

            }

            .container input[type="radio"] {
                position: absolute;
                opacity: 0;
                cursor: pointer;
            }
        </style>

    </head>


    <div class="checkout">
        <div class="container m-auto row row-cols-auto">
            <div class="col overview">
                @isset($products)
                    <div class="head">Order overview <span>(@isset($qty)
                                1
                            @else
                                {{ getCartCount() }} @if (getCartCount() > 1)
                                    items
                                @else
                                    item
                                @endif
                            @endisset)</span></div>
                    <div class="products">
                        @foreach ($products as $product)
                            <div class="item">
                                <div class="details">
                                    <div class="image">
                                        <img src="{{ validate_image($product->image_path) }}" alt="">
                                    </div>
                                    <div class="dtls">
                                        <div class="name"><b>{{ $product->product_name }}</b></div>
                                        <div class="cat">varient: {{ $product->v_name }}</div>
                                        <div class="price_qty">
                                            <div class="qty">Qty: @isset($qty)
                                                    {{ $qty }}
                                                @else
                                                    {{ $product->cart_qty }}
                                                @endisset
                                            </div>
                                            <div class="price">
                                                @isset($qty)
                                                    {{ currency($product->sales_price * $qty) }}
                                                @else
                                                    {{ currency($product->sales_price * $product->cart_qty) }}
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endisset
            </div>

            <div class="col address">
                <div class="head">Delivery address</div>

                @if (getAddress('shipping')['has'] == true)
                    <div class="txt_field">
                        <div class="label">Delivery address <span>*</span></div>
                        <div class="input"><input type="text" name="address1" id="address1"
                                placeholder="delivery address" value="{{ getAddress('shipping')['address1'] }}"></div>
                    </div>

                    <div class="txt_field">
                        <div class="label">Postal code <span>*</span></div>
                        <div class="input"><input type="text" name="postal" id="postal" placeholder="postal code"
                                value="{{ getAddress('shipping')['postal'] }}"></div>
                    </div>

                    <div class="txt_field">
                        <div class="label">City <span>*</span></div>
                        <div class="input"><input type="text" name="city" id="city" placeholder="city"
                                value="{{ getAddress('shipping')['city'] }}" readonly></div>
                    </div>

                    <div class="txt_field">
                        <div class="label">Country <span>*</span></div>
                        <div class="input">
                            <select name="country" id="country">
                            @empty(getAddress('shipping')['country'])
                                <option value="">-- Select Country --</option>
                            @else
                                <option value="{{ getAddress('shipping')['country'] }}">
                                    {{ getAddress('shipping')['country'] }}</option>
                                <option value="" disabled></option>
                            @endempty
                            @foreach (country('get') as $del_country)
                                <option value="{{ $del_country }}">{{ $del_country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <form id="addressForm">

                    <div class="all-steps" id="all-steps">
                        <span class="step"></span> <span class="step"></span>
                    </div>
                    <div class="tab">



                        <div class="txt_field" id="addressCheckbox">
                            <input type="checkbox" id="addressCheckboxinput" name="address">
                            <label class="labal" for="addressCheckbox" name="addressCheckbox">Use My Shopping
                                Address</label>
                            <br><br>OR
                        </div>

                        <!-- Add a message to display if the user has no existing address -->
                        <div id="noAddressError" style="display: none; color: red;">
                            You do not have an existing address. Please fill in the address fields.
                        </div>
                        <div class="txt_field">
                            <div class="label">Country <span>*</span></div>
                            <div class="input">
                                <select name="country" id="country">
                                    @foreach (country('get') as $del_country)
                                        <option value="{{ $del_country }}">{{ $del_country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="tab">
                        <div class="txt_field">
                            <div class="label">Delivery address <span>*</span></div>
                            <div class="input"><input type="text" name="address1" id="address"
                                    placeholder="delivery address" value="{{ getAddress('shipping')['address1'] }}">
                            </div>
                        </div>



                        <div class="txt_field">
                            <div class="label">Postal code <span>*</span></div>
                            <div class="input">
                                <input type="text" name="postal" id="postal" placeholder="postal code"
                                    value="{{ getAddress('shipping')['postal'] }}">
                            </div>
                        </div>

                        <div class="txt_field">
                            <div class="label">City <span>*</span></div>
                            <div class="input">
                                <input type="text" name="city" id="city" placeholder="city"
                                    value="{{ getAddress('shipping')['city'] }}">
                            </div>
                        </div>
                    </div>


                    <div style="overflow:auto;" id="nextprevious">
                        <div style="float:right;">
                            <button class="navigate_btn" type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                            <button class="navigate_btn" type="button" id="nextBtn" onclick="location.href='/account/address-book'" alt="">Go</button>


                        </div>


                    </div>
                </form>
            @endif


        </div>

        <div class="col summary">
            <div class="head">Order summary</div>

            <div class="amount_wrap">
                <div class="sub_total">
                    <div class="txt">Sub total :</div>
                    <div class="amount" id="order_sub_total">
                        @isset($qty)
                            {{ currency($products[0]->sales_price * $qty) }}
                        @else
                            {{ get_cart_total() }}
                        @endisset
                    </div>
                </div>

                <div class="sub_total">
                    <div class="txt">Delivery charge :</div>
                    <div class="amount" id="order_delivery">
                        @isset($qty)
                            {{ currency(getDelivery($products[0]->sku, $qty)) }}
                        @else
                            {{ currency(getDelivery($products)) }}
                        @endisset
                    </div>
                </div>

                <div class="sub_total">
                    <div class="txt">Total weight :</div>
                    <div class="amount">
                        @isset($qty)
                            {{ $products[0]->weight * $qty }} kg
                        @else
                            {{ getTotalWeight($products) }} kg
                        @endisset
                    </div>
                </div>
            </div>

            <div class="total">
                <div class="txt">ORDER TOTAL</div>
                <div class="amount" id="order_total">
                    @isset($qty)
                        {{ currency($products[0]->sales_price * $qty + getDelivery($products[0]->sku, $qty)) }}
                    @else
                        {{ currency(get_cart_total(false) + getDelivery($products)) }}
                    @endisset
                </div>
            </div>



            @isset($qty)
                <div id="checkout_btn">
                    <div class="proceed">
                        <checkout-btn :sku="'{{ $products[0]->sku }}'" :qty="'{{ $qty }}'"><checkout-btn />
                    </div>
                </div>
            @else
                <div id="cartcheckout_btn">
                    <div class="proceed">
                        <cartcheckout-btn :recurring_cart="{{ isset($_GET['recurring_cart'])? $_GET['recurring_cart'] : false }}" />
                    </div>
                </div>
            @endisset

        </div>
    </div>
</div>
<script></script>
<script>
    @isset($qty)

        $("#country").change(function() {
            $.ajax({
                type: "post",
                url: "/get-total",
                data: {
                    get_total: "product",
                    country: this.value,
                    sku: '{{ $products[0]->sku }}',
                    qty: '{{ $qty }}',
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.error == 0) {
                        $("#order_sub_total").html(response.subtotal);
                        $("#order_delivery").html(response.del);
                        $("#order_total").html(response.total);
                    } else {
                        toastr.error(response.msg, "Error");
                    }
                }
            });
        });
    @else

        $("#country").change(function() {
            $.ajax({
                type: "post",
                url: "/get-total",
                data: {
                    get_total: "cart",
                    country: this.value,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.error == 0) {
                        $("#order_sub_total").html(response.subtotal);
                        $("#order_delivery").html(response.del);
                        $("#order_total").html(response.total);
                    } else {
                        toastr.error(response.msg, "Error");
                    }
                }
            });
        });
    @endisset
</script>
<script>
    $(document).ready(function() {
        // Define the mapping of postal codes to cities
        var postalCodeMapping = {
            // Central Province Post Codes: Sri Lanka
            '22094': 'Agarapathana',
            '21142': 'Akuramboda',
            '20850': 'Akurana',
            '20140': 'Alawatugoda',
            '20062': 'Aludeniya',
            '21004': 'Alwatta',
            '20986': 'Ambagahapelessa',
            '20678': 'Ambagamuwa Udabulathgama',
            '21504': 'Ambana',
            '20686': 'Ambatalawa',
            '20136': 'Ambatenna',
            '22216': 'Ambewela',
            '20160': 'Ampitiya',
            '20150': 'Ankumbura',
            '20574': 'Atabage',
            '21512': 'Ataragallewa',
            '20308': 'Balana',
            '20644': 'Bambaragahaela',
            '21212': 'Bambaragaswewa',
            '20967': 'Barawardhana Oya',
            '20154': 'Batagolladeniya',
            '20132': 'Batugoda',
            '20966': 'Batumulla',
            '20218': 'Bawlana',
            '21214': 'Beligamuwa',
            '22060': 'Bogawantalawa',
            '20932': 'Bopana',
            '22095': 'Bopattalawa',
            '20684': 'Dagampitiya',
            '21100': 'Dambulla',
            '21032': 'Dankanda',
            '20465': 'Danture',
            '22096': 'Dayagama Bazaar',
            '20068': 'Dedunupitiya',
            '20658': 'Dekinda',
            '20430': 'Deltota',
            '21552': 'Devagiriya',
            '21206': 'Dewahuwa',
            '22050': 'Dikoya',
            '20126': 'Dolapihilla',
            '20510': 'Dolosbage',
            '20532': 'Doluwa',
            '20567': 'Doragala',
            '20816': 'Doragamuwa',
            '21054': 'Dullewa',
            '21046': 'Dunkolawatta',
            '22002': 'Dunukedeniya',
            '20824': 'Dunuwila',
            '21538': 'Dunuwilapitiya',
            '20684': 'Dagampitiya',
            '21100': 'Dambulla',
            '21032': 'Dankanda',
            '20465': 'Danture',
            '22096': 'Dayagama Bazaar',
            '20068': 'Dedunupitiya',
            '20658': 'Dekinda',
            '20430': 'Deltota',
            '21552': 'Devagiriya',
            '21206': 'Dewahuwa',
            '22050': 'Dikoya',
            '20126': 'Dolapihilla',
            '20510': 'Dolosbage',
            '20532': 'Doluwa',
            '20567': 'Doragala',
            '20816': 'Doragamuwa',
            '21054': 'Dullewa',
            '21046': 'Dunkolawatta',
            '22002': 'Dunukedeniya',
            '20824': 'Dunuwila',
            '21538': 'Dunuwilapitiya',
            '20732': 'Ekiriya',
            '20742': 'Elamulla',
            '21012': 'Elkaduwa',
            '21108': 'Erawula Junction',
            '21402': 'Etanawala',
            '20202': 'Etulgama',
            '20684': 'Dagampitiya',
            '21100': 'Dambulla',
            '21032': 'Dankanda',
            '20465': 'Danture',
            '22096': 'Dayagama Bazaar',
            '20068': 'Dedunupitiya',
            '20658': 'Dekinda',
            '20430': 'Deltota',
            '21552': 'Devagiriya',
            '21206': 'Dewahuwa',
            '22050': 'Dikoya',
            '20126': 'Dolapihilla',
            '20510': 'Dolosbage',
            '20532': 'Doluwa',
            '20567': 'Doragala',
            '20816': 'Doragamuwa',
            '21054': 'Dullewa',
            '21046': 'Dunkolawatta',
            '22002': 'Dunukedeniya',
            '20824': 'Dunuwila',
            '21538': 'Dunuwilapitiya',
            '20664': 'Galaboda',
            '20100': 'Galagedara',
            '20420': 'Galaha',
            '21200': 'Galewela',
            '20152': 'Galhinna',
            '20095': 'Gallellagama',
            '21068': 'Gammaduwa',
            '20500': 'Gampola',
            '21404': 'Gangala Puwakpitiya',
            '20620': 'Gelioya',
            '20680': 'Ginigathena',
            '20214': 'Godamunna',
            '20184': 'Gomagoda',
            '20712': 'Gonagantenna',
            '22226': 'Gonakele',
            '20656': 'Gonawalapatana',
            '20270': 'Gunnepana',
            '20189': 'Gurudeniya',
            '22208': 'Haggala',
            '22240': 'Halgranoya',
            '20032': 'Halloluwa',
            '20984': 'Handaganawa',
            '20438': 'Handawalapitiya',
            '20480': 'Handessa',
            '21536': 'Handungamuwa',
            '22044': 'Hangarapitiya',
            '20710': 'Hanguranketha',
            '20668': 'Hapugastalawa',
            '20669': 'Harangalagama',
            '20092': 'Harankahawa',
            '22262': 'Harasbedda',
            '20960': 'Hasalaka',
            '20060': 'Hataraliyadda',
            '22000': 'Hatton',
            '21514': 'Hattota Amuna',
            '22024': 'Hedunuwewa',
            '20440': 'Hewaheta',
            '20414': 'Hindagala',
            '22046': 'Hitigegama',
            '20524': 'Hondiyadeniya',
            '20948': 'Hunnasgiriya',
            '21064': 'Imbulgolla',
            '21124': 'Inamaluwa',
            '20822': 'Jambugahapitiya',
            '20300': 'Kadugannawa',
            '20924': 'Kahataliyadda',
            '21066': 'Kaikawala',
            '22282': 'Kalaganwatta',
            '20926': 'Kalugala',
            '21112': 'Kalundawa',
            '21106': 'Kandalama',
            '22220': 'Kandapola',
            '20000': 'Kandy',
            '20206': 'Kapuliyadde',
            '21014': 'Karagahinna',
            '20738': 'Karandagolla',
            '21016': 'Katudeniya',
            '20800': 'Katugastota',
            '20588': 'Katukitula',
            '21072': 'Kavudupelella',
            '22274': 'Keerthi Bandarapura',
            '20688': 'Kelanigama',
            '20186': 'Kengalla',
            '20660': 'Ketaboola',
            '20306': 'Ketakumbura',
            '20198': 'Ketawala Leula',
            '21122': 'Kibissa',
            '20442': 'Kiribathkumbura',
            '21042': 'Kiwula',
            '20928': 'Kobonila',
            '20212': 'Kolabissa',
            '20971': 'Kolongoda',
            '21500': 'Kongahawela',
            '22080': 'Kotagala',
            '20560': 'Kotmale',
            '22040': 'Kottellena',
            '20048': 'Kulugammana',
            '22272': 'Kumbalgamuwa',
            '20902': 'Kumbukkandura',
            '22246': 'Kumbukwela',
            '20086': 'Kumburegama',
            '20168': 'Kundasale',
            '22252': 'Kurupanawela',
            '20592': 'Labukele',
            '21520': 'Laggala Pallegama',
            '22034': 'Laxapana',
            '20482': 'Leemagahakotuwa',
            '21008': 'Leliambe',
            '21094': 'Lenadora',
            '20042': 'Lhala Kobbekaduwa',
            '22090': 'Lindula',
            '20724': 'Lllagolla',
            '21406': 'Lllukkumbura',
            '20172': 'Lunuketiya Maditta',
            '20260': 'Madawala Bazaar',
            '21074': 'Madawala Ulpotha',
            '21156': 'Madipola',
            '20938': 'Madugalla',
            '20840': 'Madulkele',
            '22256': 'Madulla',
            '20945': 'Mahadoraliyadda',
            '20216': 'Mahamedagama',
            '21140': 'Mahawela',
            '20702': 'Mailapitiya',
            '20828': 'Makkanigama',
            '20921': 'Makuldeniya',
            '22021': 'Maldeniya',
            '21144': 'Mananwatta',
            '20744': 'Mandaram Nuwara',
            '20662': 'Mapakanda',
            '21554': 'Maraka',
            '20210': 'Marassana',
            '20714': 'Marymount Colony',
            '22070': 'Maskeliya',
            '20566': 'Maswela',
            '21000': 'Matale',
            '20748': 'Maturata',
            '20564': 'Mawatura',
            '20940': 'Medamahanuwara',
            '20120': 'Medawala Harispattuwa',
            '20512': 'Meetalawa',
            '20409': 'Megoda Kalugamuwa',
            '21055': 'Melipitiya',
            '20470': 'Menikdiwela',
            '20170': 'Menikhinna',
            '21062': 'Metihakka',
            '21114': 'Millawana',
            '20923': 'Mimure',
            '20109': 'Minigamuwa',
            '20983': 'Minipe',
            '22254': 'Mipanawa',
            '22214': 'Mipilimana',
            '22036': 'Morahenagama',
            '20752': 'Munwatta',
            '20232': 'Murutalawa',
            '20526': 'Muruthagahamulla',
            '21044': 'Muwandeniya',
            '21082': 'Nalanda',
            '22150': 'Nanuoya',
            '20176': 'Naranpanawa',
            '20194': 'Nattarampotha',
            '21090': 'Naula',
            '20650': 'Nawalapitiya',
            '20670': 'Nawathispane',
            '20568': 'Nayapana Janapadaya',
            '22280': 'Nildandahinna',
            '20418': 'Nillambe',
            '22075': 'Nissanka Uyana',
            '22058': 'Norwood',
            '21534': 'Nugagolla',
            '20204': 'Nugaliyadda',
            '20072': 'Nugawela',
            '22200': 'Nuwara Eliya',
            '21076': 'Opalgala',
            '21020': 'Ovilikanda',
            '20750': 'Padiyapelella',
            '21070': 'Palapathwela',
            '20734': 'Pallebowala',
            '20084': 'Pallekotuwa',
            '21152': 'Pallepola',
            '20830': 'Panvila',
            '20544': 'Panwilatenna',
            '20578': 'Paradeka',
            '20654': 'Pasbage',
            '22012': 'Patana',
            '20511': 'Pattitalawa',
            '20118': 'Pattiya Watta',
            '20400': 'Peradeniya',
            '21532': 'Perakanatta',
            '20196': 'Pilawala',
            '20450': 'Pilimatalawa',
            '20682': 'Pitawala',
            '20106': 'Poholiyadda',
            '20250': 'Polgolla',
            '21502': 'Pubbiliya',
            '20112': 'Pujapitiya',
            '22120': 'Pundaluoya',
            '20546': 'Pupuressa',
            '20580': 'Pussellawa',
            '20906': 'Putuhapuwa',
            '20180': 'Rajawella',
            '20590': 'Ramboda',
            '20676': 'Rambukpitiya',
            '20128': 'Rambukwella',
            '21524': 'Ranamuregama',
            '20922': 'Rangala',
            '20990': 'Rantembe',
            '20818': 'Rathukohodigala',
            '21400': 'Rattota',
            '20730': 'Rikillagaskada',
            '22008': 'Rozella',
            '22245': 'Rupaha',
            '22212': 'Ruwaneliya',
            '20044': 'Sangarajapura',
            '22202': 'Santhipura',
            '21058': 'Selagama',
            '20904': 'Senarathwela',
            '21120': 'Sigiriya',
            '21506': 'Talagoda Junction',
            '21116': 'Talakiriyagama',
            '20200': 'Talatuoya',
            '22100': 'Talawakele',
            '20838': 'Tawalantenna',
            '20900': 'Teldeniya',
            '20166': 'Tennekumbura',
            '22287': 'Teripeha',
            '20404': 'Uda Peradeniya',
            '20506': 'Udahentenna',
            '20094': 'Udahingulwala',
            '22285': 'Udamadura',
            '22250': 'Udapussallawa',
            '21051': 'Udasgiriya',
            '20802': 'Udatalawinna',
            '21006': 'Udatenna',
            '20722': 'Udawatta',
            '20916': 'Udispattuwa',
            '20950': 'Ududumbara',
            '20052': 'Uduwa',
            '20934': 'Uduwahinna',
            '20164': 'Uduwela',
            '21300': 'Ukuwela',
            '20562': 'Ulapane',
            '20965': 'Ulpothagama',
            '20708': 'Unuwinna',
            '20640': 'Velamboda',
            '21160': 'Wahacotte',
            '22270': 'Walapane',
            '21048': 'Walawela',
            '22110': 'Watagoda',
            '20134': 'Watagoda Harispattuwa',
            '22010': 'Watawala',
            '20454': 'Wattappola',
            '20810': 'Wattegama',
            '21009': 'Wehigala',
            '21408': 'Welangahawatte',
            '20610': 'Weligalla',
            '20666': 'Weligampola',
            '20914': 'Wendaruwa',
            '20982': 'Weragantota',
            '20908': 'Werapitya',
            '20080': 'Werellagama',
            '20108': 'Wettawa',
            '21114': 'Wewalawewa',
            '22032': 'Widulipura',
            '22018': 'Wijebahukanda',
            '20142': 'Wilanagama',
            '21530': 'Wilgamuwa',
            '20234': 'Yahalatenna',
            '21056': 'Yatawatta',
            '20034': 'Yatihalagala',
            // Northern Province Post Codes: Sri Lanka
            '40000': 'Jaffna',
            '41000': 'Mannar',
            '42000': 'Mullativu',
            '43000': 'Vavuniya',
            // Western Province Post Codes: Sri Lanka
            '12200': 'Agalawatta',
            '11536': 'Akaragama',
            '10732': 'Akarawita',
            '11122': 'Alawala',
            '12524': 'Alubomulla',
            '12080': 'Alutgama',
            '11052': 'Ambagaspitiya',
            '11212': 'Ambepussa',
            '11558': 'Andiambalama',
            '12320': 'Anguruwatota',
            '10150': 'Athurugiriya',
            '11120': 'Attanagalla',
            '10700': 'Avissawella',
            '11538': 'Badalgama',
            '12230': 'Baduraliya',
            '00400': 'Bambalapitiya',
            '12530': 'Bandaragama',
            '11244': 'Banduragoda',
            '10513': 'Batawala',
            '10120': 'Battaramulla',
            '10526': 'Batugampola',
            '11011': 'Batuwatta',
            '12224': 'Bellana',
            '11040': 'Bemmulla',
            '12070': 'Beruwala',
            '11650': 'Biyagama',
            '11672': 'Biyagama Ipz',
            '11216': 'Bokalagama',
            '11024': 'Bollete',
            '12008': 'Bolossagama',
            '12024': 'Bombuwala',
            '11134': 'Bopagama',
            '10522': 'Bope',
            '10290': 'Boralesgamuwa',
            '12142': 'Boralugoda',
            '00800': 'Borella',
            '12300': 'Bulathsinhala',
            '11720': 'Buthpitiya',
            '00300': 'Colpetty',
            '11524': 'Dagonna',
            '12148': 'Danawala Thiniyawala',
            '11896': 'Danowita',
            '10656': 'Dedigamuwa',
            '10350': 'Dehiwala',
            '11690': 'Dekatana',
            '11700': 'Delgoda',
            '12304': 'Delmella',
            '10302': 'Deltara',
            '11228': 'Delwagura',
            '11692': 'Demalagama',
            '11270': 'Demanhandiya',
            '11102': 'Dewalapola',
            '12090': 'Dharga Town',
            '11250': 'Divulapitiya',
            '11208': 'Divuldeniya',
            '12308': 'Diwalakada',
            '12020': 'Dodangoda',
            '12416': 'Dombagoda',
            '11680': 'Dompe',
            '11264': 'Dunagaha',
            '11380': 'Ekala',
            '11116': 'Ellakkala',
            '11108': 'Essella',
            '00100': 'Fort',
            '12005': 'Galpatha',
            '12016': 'Gamagoda',
            '11000': 'Gampaha',
            '11020': 'Ganemulla',
            '12410': 'Gonapola Junction',
            '11630': 'Gonawalawp',
            '12310': 'Govinna',
            '12236': 'Gurulubadda',
            '10204': 'Habarakada',
            '12055': 'Halkandawila',
            '12538': 'Haltota',
            '12118': 'Halwala',
            '12306': 'Halwatura',
            '10524': 'Handapangoda',
            '10650': 'Hanwella',
            '00500': 'Havelock Town',
            '12234': 'Hedigalla Colony',
            '11618': 'Heiyanthuduwa',
            '11715': 'Henegama',
            '10714': 'Hewainna',
            '11568': 'Hinatiyana Madawala',
            '10232': 'Hiripitya',
            '11734': 'Hiswella',
            '10118': 'Hokandara',
            '10200': 'Homagama',
            '10502': 'Horagala',
            '11564': 'Horampella',
            '12400': 'Horana',
            '11262': 'Hunumulla',
            '11265': 'Ihala Madampella',
            '11856': 'Imbulgoda',
            '12116': 'Ittapana',
            '11350': 'Ja-Ela',
            '11850': 'Kadawatha',
            '10640': 'Kaduwela',
            '11144': 'Kahatowita',
            '10508': 'Kahawala',
            '11875': 'Kalagedihena',
            '10718': 'Kalatuwawa',
            '12078': 'Kalawila Kiranthidiya',
            '11160': 'Kaleliya',
            '11224': 'Kaluaggala',
            '12000': 'Kalutara',
            '12418': 'Kananwila',
            '11320': 'Kandana',
            '12428': 'Kandanagama',
            '10662': 'Kapugoda',
            '11534': 'Katana',
            '11420': 'Katunayake 11450',
            '11440': 'Katunayake Air Force Camp',
            '11526': 'Katuwellegama',
            '12550': 'Kehelwatta',
            '11600': 'Kelaniya',
            '12218': 'Kelinkanda',
            '11522': 'Kimbulapitiya',
            '11730': 'Kirindiwela',
            '10208': 'Kiriwattuduwa',
            '00600': 'Kirulapone',
            '11206': 'Kitalawalana',
            '12222': 'Kitulgoda',
            '11242': 'Kitulwala',
            '11540': 'Kochchikade',
            '12007': 'Koholana',
            '10600': 'Kolonnawa',
            '10730': 'Kosgama',
            '11232': 'Kotadeniyawa',
            '01300': 'Kotahena',
            '11390': 'Kotugoda',
            '12426': 'Kuda Uduwa',
            '11105': 'Kumbaloluwa',
            '12440': 'Lngiriya',
            '11204': 'Loluwagoda',
            '11062': 'Lunugama',
            '11114': 'Mabodale',
            '10306': 'Madapatha',
            '11033': 'Madelgamuwa',
            '12060': 'Maggona',
            '12210': 'Mahagama',
            '12126': 'Mahakalupahana',
            '10280': 'Maharagama',
            '11358': 'Makewita',
            '11640': 'Makola',
            '10115': 'Malabe',
            '11670': 'Malwana',
            '11061': 'Mandawala',
            '11260': 'Marandagahamula',
            '12100': 'Matugama',
            '12130': 'Meegahatenna',
            '12094': 'Meegama',
            '10504': 'Meegoda',
            '11234': 'Mellawagedara',
            '12412': 'Millaniya',
            '12422': 'Millewa',
            '11550': 'Minuwangoda',
            '11200': 'Mirigama',
            '11742': 'Mithirigala',
            '12424': 'Miwanapalana',
            '12216': 'Molkawa',
            '12232': 'Morapitiya',
            '10400': 'Moratuwa',
            '12564': 'Morontuduwa',
            '10370': 'Mount Lavinia',
            '11112': 'Muddaragama',
            '11056': 'Mudungoda',
            '10202': 'Mullegama',
            '10620': 'Mulleriyawa New Town',
            '01500': 'Mutwal',
            '10704': 'Napawela',
            '11063': 'Naranwala',
            '11222': 'Nawana',
            '12106': 'Nawattuduwa',
            '12030': 'Neboda',
            '11066': 'Nedungamuwa',
            '11500': 'Negombo',
            '11128': 'Nikahetikanda',
            '11880': 'Nittambuwa',
            '11354': 'Niwandama',
            '10250': 'Nugegoda',
            '12074': 'Padagoda',
            '10500': 'Padukka',
            '12144': 'Pahalahewessa',
            '12050': 'Paiyagala',
            '11150': 'Pallewela',
            '11370': 'Pamunugama',
            '11214': 'Pamunuwatta',
            '12500': 'Panadura',
            '12114': 'Pannila',
            '10230': 'Pannipitiya',
            '12414': 'Paragastota',
            '12302': 'Paragoda',
            '12122': 'Paraigama',
            '11890': 'Pasyala',
            '12214': 'Pelanda',
            '12138': 'Pelawatta',
            '11830': 'Peliyagoda',
            '11741': 'Pepiliyawala',
            '11043': 'Pethiyagoda',
            '10300': 'Piliyandala',
            '10206': 'Pitipana Homagama',
            '12404': 'Pokunuwita',
            '12136': 'Polgampola',
            '10320': 'Polgasowita',
            '11324': 'Polpithimukulana',
            '12432': 'Poruwedanda',
            '10660': 'Pugoda',
            '10712': 'Puwakpitiya',
            '11892': 'Radawadunna',
            '11725': 'Radawana',
            '11400': 'Raddolugama',
            '11010': 'Ragama',
            '10654': 'Ranala',
            '11856': 'Remunagoda',
            '11142': 'Ruggahawila',
            '11129': 'Rukmale',
            '11410': 'Seeduwa',
            '10304': 'Siddamulla',
            '11607': 'Siyambalape',
            '00200': 'Slave Island',
            '10100': 'Sri Jayawardenepura Kotte',
            '11504': 'Talahena',
            '10116': 'Talawatugoda',
            '12025': 'Tebuwana',
            '11532': 'Thimbirigaskatuwa',
            '10664': 'Tittapattara',
            '10682': 'Tummodara',
            '11054': 'Udathuthiripitiya',
            '11030': 'Udugampola',
            '12322': 'Uduwara',
            '11034': 'Uggalboda',
            '11126': 'Urapola',
            '11328': 'Uswetakeiyawa',
            '12127': 'Utumgama',
            '12204': 'Veyangalla',
            '11100': 'Veyangoda',
            '12560': 'Wadduwa',
            '10680': 'Waga',
            '12112': 'Walagedara',
            '12134': 'Walallawita',
            '11146': 'Walgammulla',
            '11226': 'Walpita',
            '11012': 'Walpola',
            '11068': 'Wanaluwewa',
            '12580': 'Waskaduwa',
            '10511': 'Watareka',
            '11724': 'Wathurugama',
            '11104': 'Watinapaha',
            '11104': 'Wattala',
            '11858': 'Weboda',
            '11562': 'Wegowwa',
            '12108': 'Welipenna',
            '11710': 'Weliveriya',
            '00600': 'Wellawatta',
            '12534': 'Welmilla Junction',
            '11894': 'Weweldeniya',
            '12124': 'Yagirala',
            '11870': 'Yakkala',
            '12104': 'Yatadolawatta',
            '12006': 'Yatawara Junction',
            '11566': 'Yatiyana',
            // Eastern Province Post Codes: Sri Lanka
            '32350': 'Addalaichenai',
            '31304': 'Agbopura',
            '32400': 'Akkaraipattu',
            '32000': 'Ampara',
            '30162': 'Ampilanthurai',
            '30150': 'Araipattai',
            '30362': 'Ayithiyamalai',
            '30206': 'Bakiella',
            '32024': 'Bakmitiyawa',
            '30000': 'Batticaloa',
            '31028': 'Buckmigama',
            '32050': 'Central Camp',
            '30194': 'Cheddipalayam',
            '30350': 'Chenkaladi',
            '31050': 'Chinabay',
            '32046': 'Dadayamtalawa',
            '32014': 'Damana',
            '32126': 'Damanewela',
            '32006': 'Deegawapiya',
            '32150': 'Dehiattakandiya',
            '31226': 'Dehiwatte',
            '32038': 'Devalahinda',
            '32008': 'Digamadulla Weeragoda',
            '32104': 'Dorakumbura',
            '31236': 'Echchilampattai',
            '30300': 'Eravur',
            '32066': 'Galapitagala',
            '31318': 'Galmetiyawa',
            '31026': 'Gomarankadawala',
            '32064': 'Gonagolla',
            '32010': 'Hingurana',
            '32514': 'Hulannuge',
            '31212': 'Kaddaiparichchan',
            '30410': 'Kalkudah',
            '30250': 'Kallar',
            '32300': 'Kalmunai',
            '30200': 'Kaluwanchikudi',
            '30372': 'Kaluwankemy',
            '32405': 'Kannakipuram',
            '30016': 'Kannankudah',
            '31032': 'Kanniya',
            '31300': 'Kantalai',
            '31306': 'Kantalai Sugar Factory',
            '30354': 'Karadiyanaru',
            '32250': 'Karativu',
            '30456': 'Kathiraveli',
            '30100': 'Kattankudi',
            '32074': 'Kekirihena',
            '31220': 'Kiliveddy',
            '31100': 'Kinniya',
            '30394': 'Kiran',
            '30159': 'Kirankulam',
            '30249': 'Koddaikallar',
            '30160': 'Kokkaddichcholai',
            '32035': 'Koknahara',
            '32102': 'Kolamanthalawa',
            '32418': 'Komari',
            '31014': 'Kuchchaveli',
            '31012': 'Kumburupiddy',
            '31112': 'Kurinchakemy',
            '30192': 'Kurukkalmadam',
            '32512': 'Lahugala',
            '31234': 'Lankapatuna',
            '32450': 'Lmkkamam',
            '32016': 'Madawalalanda',
            '31036': 'Mahadivulwewa',
            '32018': 'Mahanagapura',
            '32070': 'Mahaoya',
            '31106': 'Maharugiramam',
            '31224': 'Mallikativu',
            '32198': 'Malwatta',
            '30220': 'Mandur',
            '32069': 'Mangalagama',
            '30442': 'Mankemi',
            '32314': 'Marathamune',
            '31238': 'Mawadichenai',
            '32158': 'Mawanagama',
            '30426': 'Miravodai',
            '32012': 'Moragahapallama',
            '31312': 'Mullipothana',
            '30392': 'Murakottanchanai',
            '31200': 'Mutur',
            '32037': 'Namaloya',
            '30238': 'Navagirinagar',
            '30018': 'Navatkadu',
            '32308': 'Navithanveli',
            '32120': 'Nawamedagama',
            '31228': 'Neelapola',
            '31010': 'Nilaveli',
            '32340': 'Nintavur',
            '30420': 'Oddamavadi',
            '32360': 'Oluvil',
            '32100': 'Padiyatalawa',
            '32034': 'Pahalalanda',
            '32354': 'Palamunai',
            '32508': 'Panama',
            '30444': 'Panichankemi',
            '31034': 'Pankudavely',
            '32022': 'Pannalagama',
            '32031': 'Paragahakele',
            '32316': 'Periyaneelavanai',
            '30230': 'Periyaporativu',
            '30358': 'Periyapullumalai',
            '30022': 'Pillaiyaradi',
            '32032': 'Polwaga Janapadaya',
            '32500': 'Pottuvil',
            '30428': 'Punanai',
            '30158': 'Puthukudiyiruppu',
            '32068': 'Rajagalatenna',
            '31038': 'Rottawewa',
            '32280': 'Sainthamaruthu',
            '32200': 'Samanthurai',
            '31006': 'Sampaltivu',
            '31216': 'Sampur',
            '32101': 'Serankada',
            '31232': 'Serunuwara',
            '31260': 'Seruwila',
            '31314': 'Sirajnagar',
            '32155': 'Siripura',
            '32048': 'Siyambalawewa',
            '31222': 'Somapura',
            '31046': 'Tampalakamam',
            '32072': 'Tempitiya',
            '32415': 'Thambiluvil',
            '30024': 'Thannamunai',
            '30196': 'Thettativu',
            '30236': 'Thikkodai',
            '30234': 'Thirupalugamam',
            '30254': 'Thuraineelavanai',
            '31016': 'Tiriyayi',
            '32420': 'Tirukovil',
            '31250': 'Toppur',
            '31000': 'Trincomalee',
            '32060': 'Uhana',
            '30364': 'Unnichchai',
            '30424': 'Vakaneri',
            '30450': 'Vakarai',
            '30400': 'Valaichenai',
            '30376': 'Vantharumoolai',
            '31053': 'Vellamanal',
            '30204': 'Vellavely',
            '32039': 'Wadinagala',
            '32454': 'Wanagamuwa',
            '31308': 'Wanela',
            '32062': 'Werunketagoda',
            // Province Of Uva Post Codes: Sri Lanka
            '90166': 'Akkarasiyaya',
            '90736': 'Aluketiyawa',
            '90722': 'Aluttaramma',
            '90108': 'Ambadandegama',
            '90326': 'Ambagahawatta',
            '90300': 'Ambagasdowa',
            '90204': 'Amunumulla',
            '91302': 'Angunakolawewa',
            '90017': 'Arawa',
            '90532': 'Arawakumbura',
            '90712': 'Arawatta',
            '90542': 'Atakiriya',
            '91516': 'Ayiwela',
            '91070': 'Badalkumbura',
            '90000': 'Badulla',
            '90019': 'Baduluoya',
            '91058': 'Baduluwela',
            '91554': 'Bakinigahawela',
            '91295': 'Balaharuwa',
            '90092': 'Ballaketuwa',
            '90322': 'Bambarapana',
            '90100': 'Bandarawela',
            '90066': 'Beramada',
            '91500': 'Bibile',
            '90502': 'Bibilegama',
            '90354': 'Bogahakumbura',
            '90362': 'Boragas',
            '90170': 'Boralanda',
            '90302': 'Bowela',
            '91038': 'Buddama',
            '91100': 'Buttala',
            '91050': 'Dambagalla',
            '90714': 'Dambana',
            '90080': 'Demodara',
            '90132': 'Diganatenna',
            '90214': 'Dikkapitiya',
            '90324': 'Dimbulana',
            '90726': 'Divulapelessa',
            '91514': 'Diyakobala',
            '90150': 'Diyatalawa',
            '91010': 'Dombagahawela',
            '90104': 'Dulgolla',
            '90013': 'Egodawela',
            '70254': 'Ekamutugama',
            '91502': 'Ekiriyankumbura',
            '90090': 'Ella',
            '91020': 'Ethimalewewa',
            '90140': 'Ettampitiya',
            '91250': 'Ettiliwewa',
            '91008': 'Galabedda',
            '90065': 'Galauda',
            '90206': 'Galedanda',
            '90752': 'Galporuyaya',
            '90512': 'Gamewela',
            '90082': 'Gawarawela',
            '90750': 'Girandurukotte',
            '90067': 'Godunna',
            '90208': 'Gurutalawa',
            '90180': 'Haldummulla',
            '90060': 'Hali Ela',
            '91308': 'Hambegamuwa',
            '90224': 'Hangunnawa',
            '90160': 'Haputale',
            '90724': 'Hebarawa',
            '90112': 'Heeloya',
            '90122': 'Helahalpe',
            '90094': 'Helapupula',
            '90358': 'Hewanakumbura',
            '90508': 'Hingurukaduwa',
            '90524': 'Hopton',
            '91004': 'Hulandawa',
            '96167': 'Idalgashinna',
            '91040': 'Inginiyagala',
            '90052': 'Kahataruppa',
            '90352': 'Kalubululanda',
            '90546': 'Kalugahakandura',
            '90186': 'Kalupahana',
            '90020': 'Kandaketya',
            '91032': 'Kandaudapanguwa',
            '90070': 'Kandawinna',
            '90048': 'Kandegedara',
            '90356': 'Kandepuhulpola',
            '91400': 'Kataragama',
            '90102': 'Kebillawela',
            '90046': 'Kendagolla',
            '90350': 'Keppetipola',
            '90738': 'Keselpotha',
            '90016': 'Ketawatta',
            '70252': 'Kiriibbanwewa',
            '90184': 'Kiriwanagama',
            '90190': 'Koslanda',
            '91512': 'Kotagama',
            '90506': 'Kotamuduna',
            '91312': 'Kotawehera Mankada',
            '91024': 'Kotiyagala',
            '91098': 'Kumbukkana',
            '90728': 'Kuruwitenna',
            '90046': 'Kuttiyagolla',
            '90068': 'Landewela',
            '90106': 'Liyangahawela',
            '90530': 'Lunugala',
            '90310': 'Lunuwatta',
            '90535': 'Madulsima',
            '70256': 'Mahagama Colony',
            '90700': 'Mahiyanganaya',
            '90114': 'Makulella',
            '90754': 'Malgoda',
            '90022': 'Maliyadda',
            '90730': 'Mapakadawewa',
            '91006': 'Marawa',
            '91052': 'Mariarawa',
            '90328': 'Maspanna',
            '90582': 'Maussagolla',
            '91550': 'Medagana',
            '90218': 'Medawela Udukinda',
            '90518': 'Medawelagama',
            '90015': 'Meegahakiula',
            '90540': 'Metigahatenna',
            '90134': 'Mirahawatta',
            '90504': 'Miriyabedda',
            '90584': 'Miyanakandura',
            '91000': 'Monaragala',
            '91108': 'Moretuwegama',
            '91003': 'Nakkala',
            '90580': 'Namunukula',
            '91519': 'Nannapurawa',
            '90064': 'Narangala',
            '91042': 'Nelliyadda',
            '90042': 'Nelumgama',
            '90165': 'Nikapotha',
            '91508': 'Nilgala',
            '90216': 'Nugatalawa',
            '91007': 'Obbegoda',
            '90168': 'Ohiya',
            '91060': 'Okkampitiya',
            '90756': 'Pahalarathkinda',
            '90534': 'Pallekiruwa',
            '91002': 'Pangura',
            '90500': 'Passara',
            '90071': 'Pathanewatta',
            '90138': 'Pattiyagedara',
            '90522': 'Pelagahatenna',
            '90222': 'Perawella',
            '91505': 'Pitakumbura',
            '90544': 'Pitamaruwa',
            '90171': 'Pitapola',
            '90212': 'Puhulpola',
            '91204': 'Randeniya',
            '90164': 'Ratkarawwa',
            '90704': 'Ridimaliyadda',
            '90026': 'Rilpola',
            '91056': 'Ruwalwela',
            '91405': 'Sella Kataragama',
            '70250': 'Sewanagala',
            '90364': 'Silmiyapura',
            '90044': 'Sirimalgoda',
            '91202': 'Siyambalagune',
            '91030': 'Siyambalanduwa',
            '90718': 'Sorabora Colony',
            '90183': 'Soragune',
            '90008': 'Soranatota',
            '90028': 'Spring Valley',
            '91306': 'Suriara',
            '90014': 'Taldena',
            '91300': 'Tanamalwila',
            '90072': 'Tennepanguwa',
            '90012': 'Timbirigaspitiya',
            '90226': 'Uduhawara',
            '90702': 'Uraniya',
            '90062': 'Uva Deegalla',
            '91054': 'Uva Gangodagama',
            '90091': 'Uva Karandagolla',
            '91298': 'Uva Kudaoya',
            '90192': 'Uva Mawelagama',
            '91112': 'Uva Pelwatta',
            '90188': 'Uva Tenna',
            '90734': 'Uva Tissapura',
            '90061': 'Uva Uduwara',
            '90230': 'Uvaparanagama',
            '91198': 'Warunagama',
            '91005': 'Wedikumbura',
            '91206': 'Weherayaya Handapanagala',
            '90200': 'Welimada',
            '91200': 'Wellawaya',
            '90716': 'Wewatta',
            '91022': 'Wilaoya',
            '90034': 'Wineethagama',
            '90329': 'Yalagamuwa',
            '90706': 'Yalwela',
            //North Central Province Post Codes: Sri Lanka
            '51014': 'Alutwewa',
            '50112': 'Andiyagala',
            '50248': 'Angamuwa',
            '50000': 'Anuradhapura',
            '51100': 'Aralaganwila',
            '51072': 'Aselapura',
            '51235': 'Attanakadawala',
            '50169': 'Awukana',
            '51250': 'Bakamuna',
            '50566': 'Bogahawewa',
            '51092': 'Dalukana',
            '51106': 'Damminna',
            '50356': 'Dematawewa',
            '51094': 'Dewagala',
            '51031': 'Dimbulagala',
            '51428': 'Divulankadawala',
            '51104': 'Divuldamana',
            '51225': 'Diyabeduma',
            '51504': 'Diyasenpura',
            '50214': 'Dunumadalawa',
            '50393': 'Dutuwewa',
            '51258': 'Elahera',
            '50014': 'Elayapattuwa',
            '51034': 'Ellewewa',
            '50260': 'Eppawala',
            '50584': 'Etawatunuwewa',
            '50518': 'Etaweeragollewa',
            '50210': 'Galadivulwewa',
            '51416': 'Galamuna',
            '50390': 'Galenbindunuwewa',
            '50006': 'Galkadawala',
            '50120': 'Galkiriyagama',
            '50064': 'Galkulama',
            '50170': 'Galnewa',
            '51375': 'Galoya Junction',
            '50057': 'Gambirigaswewa',
            '50142': 'Ganewalpola',
            '50224': 'Gemunupura',
            '50392': 'Getalawa',
            '51026': 'Giritale',
            '50036': 'Gnanikulama',
            '50554': 'Gonahaddenawa',
            '50150': 'Habarana',
            '50124': 'Halmillawa Dambulla',
            '50552': 'Halmillawetiya',
            '51098': 'Hansayapalama',
            '50044': 'Hidogama',
            '51408': 'Hingurakdamana',
            '51400': 'Hingurakgoda',
            '50350': 'Horawpatana',
            '50222': 'Horiwila',
            '50176': 'Hurigaswewa',
            '50394': 'Hurulunikawewa',
            '51024': 'Jayanthipura',
            '51246': 'Jayasiripura',
            '50282': 'Kagama',
            '50320': 'Kahatagasdigiliya',
            '50562': 'Kahatagollewa',
            '50288': 'Kalakarambewa',
            '50174': 'Kalankuttiya',
            '50226': 'Kalaoya',
            '50556': 'Kalawedi Ulpotha',
            '51002': 'Kalingaela',
            '50454': 'Kallanchiya',
            '51037': 'Kalukele Badanagala',
            '50370': 'Kapugallawa',
            '50232': 'Karagahawewa',
            '51032': 'Kashyapapura',
            '50261': 'Katiyawa',
            '51414': 'Kawudulla',
            '51514': 'Kawuduluwewa Stagell',
            '50500': 'Kekirawa',
            '50452': 'Kendewa',
            '50259': 'Kiralogama',
            '50511': 'Kirigalwewa',
            '50132': 'Kitulhitiyawa',
            '51244': 'Kottapitiya',
            '51412': 'Kumaragama',
            '50062': 'Kurundankulama',
            '50068': 'Labunoruwa',
            '51006': 'Lakshauyana',
            '50262': 'Lhala Halmillewa',
            '50304': 'Lhalagama',
            '50280': 'Lpologama',
            '50130': 'Madatugama',
            '51108': 'Maduruoya',
            '51518': 'Maha Ambagaswewa',
            '50126': 'Maha Elagamuwa',
            '50196': 'Mahabulankulama',
            '50270': 'Mahailluppallama',
            '50306': 'Mahakanadarawa',
            '50327': 'Mahapothana',
            '50574': 'Mahasenpura',
            '51506': 'Mahatalakolawewa',
            '51076': 'Mahawela Sinhapura',
            '50022': 'Mahawilachchiya',
            '50384': 'Mailagaswewa',
            '50236': 'Malwanagama',
            '51090': 'Mampitiya',
            '50182': 'Maneruwa',
            '50080': 'Maradankadawala',
            '50308': 'Maradankalla',
            '50500': 'Medawachchiya',
            '51500': 'Medirigiriya',
            '51508': 'Meegaswewa',
            '50334': 'Megodawewa',
            '50300': 'Mihintale',
            '51410': 'Minneriya',
            '50349': 'Morakewa',
            '50324': 'Mulkiriyawa',
            '50344': 'Muriyakadawala',
            '51064': 'Mutugala',
            '50046': 'Nachchaduwa',
            '50339': 'Namalpura',
            '51066': 'Nawasenapura',
            '50180': 'Negampaha',
            '51096': 'Nelumwewa',
            '50200': 'Nochchiyagama',
            '51004': 'Onegama',
            '51256': 'Orubendi Siyambalawa',
            '50572': 'Padavi Maithripura',
            '50582': 'Padavi Parakramapura',
            '50587': 'Padavi Sripura',
            '50588': 'Padavi Sritissapura',
            '50570': 'Padaviya',
            '50338': 'Padikaramaduwa',
            '50206': 'Pahala Halmillewa',
            '50220': 'Pahala Maragahawe',
            '50244': 'Pahalagama',
            '50111': 'Palagala',
            '51046': 'Palugasdamana',
            '50144': 'Palugaswewa',
            '50448': 'Pandukabayapura',
            '50029': 'Pandulagama',
            '51016': 'Parakramasamudraya',
            '50326': 'Parakumpura',
            '50354': 'Parangiyawadiya',
            '50055': 'Parasangahawewa',
            '51033': 'Pelatiyawa',
            '50020': 'Pemaduwa',
            '50004': 'Perimiyankulama',
            '50512': 'Pihimbiyagolewa',
            '51102': 'Pimburattewa',
            '51050': 'Polonnaruwa',
            '50122': 'Pubbogama',
            '51046': 'Pulastigama',
            '50567': 'Pulmoddai',
            '50506': 'Punewa',
            '50246': 'Rajanganaya',
            '50450': 'Rambewa',
            '50386': 'Rampathwila',
            '50212': 'Ranorawa',
            '50514': 'Rathmalgahawewa',
            '50008': 'Saliyapura',
            '50380': 'Seeppukulama',
            '50284': 'Senapura',
            '51062': 'Sevanapitiya',
            '51378': 'Sinhagama',
            '50184': 'Siyambalewa',
            '50042': 'Sravasthipura',
            '51052': 'Sungavila',
            '50230': 'Talawa',
            '51044': 'Talpotha',
            '51089': 'Tamankaduwa',
            '51049': 'Tambala',
            '50240': 'Tambuttegama',
            '50104': 'Tammennawa',
            '50016': 'Tantirimale',
            '50242': 'Telhiriyawa',
            '50072': 'Tirappane',
            '50558': 'Tittagonewa',
            '50207': 'Udunuwara Colony',
            '51008': 'Unagalavehera',
            '50382': 'Upuldeniya',
            '50067': 'Uttimaduwa',
            '50012': 'Viharapalugama',
            '50110': 'Vijithapura',
            '50564': 'Wahalkada',
            '50492': 'Wahamalgollewa',
            '50086': 'Walagambahuwa',
            '50516': 'Walahaviddawewa',
            '51070': 'Welikanda',
            '50358': 'Welimuwapotana',
            '50586': 'Welioya Project',
            '51042': 'Wijayabapura',
            '51422': 'Yodaela',
            '51424': 'Yudaganawa',
            // Sabaragamuwa Province Post Codes: Sri Lanka
            '70082': 'Akarella',
            '71204': 'Alawatura',
            '71607': 'Algama',
            '71508': 'Alutnuwara',
            '71546': 'Ambalakanda',
            '71503': 'Ambulugala',
            '71320': 'Amitirigala',
            '71232': 'Ampagala',
            '71403': 'Anhettigama',
            '71540': 'Aranayaka',
            '71041': 'Aruggammana',
            '70294': 'Atakalanpanna',
            '71363': 'Atale',
            '70024': 'Ayagama',
            '70100': 'Balangoda',
            '70504': 'Batatota',
            '71321': 'Batuwita',
            '71044': 'Beligala',
            '70140': 'Belihuloya',
            '71706': 'Berannawa',
            '70131': 'Bolthumbe',
            '70344': 'Bomluwageaina',
            '71612': 'Bopitiya',
            '71418': 'Boralankada',
            '71208': 'Bossella',
            '71230': 'Bulathkohupitiya',
            '70346': 'Bulutota',
            '70019': 'Dambuluwana',
            '71034': 'Damunupola',
            '70455': 'Daugala',
            '71037': 'Debathgama',
            '71237': 'Dedugala',
            '71022': 'Deewala Pallegama',
            '71400': 'Dehiowita',
            '70042': 'Dela',
            '71009': 'Deldeniya',
            '71401': 'Deloluwa',
            '70046': 'Delwala',
            '70332': 'Demuwatha',
            '71430': 'Deraniyagala',
            '71050': 'Dewalegama',
            '71527': 'Dewanagala',
            '70017': 'Dodampe',
            '70404': 'Doloswalakanda',
            '71115': 'Dombemada',
            '71601': 'Dorawaka',
            '70495': 'Dumbara Manana',
            '71605': 'Dunumala',
            '70600': 'Eheliyagoda',
            '70032': 'Elapatha',
            '70492': 'Ellagawa',
            '70552': 'Ellaulla',
            '70606': 'Ellawala',
            '70200': 'Embilipitiya',
            '70506': 'Eratna',
            '70602': 'Erepola',
            '70156': 'Gabbela',
            '71603': 'Galapitamada',
            '71505': 'Galatara',
            '71350': 'Galigamuwa Town',
            '70062': 'Gallella',
            '71312': 'Galpatha',
            '70195': 'Gangeyaya',
            '71222': 'Gantuna',
            '70026': 'Gawaragiriya',
            '70620': 'Getahetta',
            '70002': 'Gillimale',
            '70556': 'Godagampola',
            '70160': 'Godakawela',
            '71318': 'Gonagala',
            '70136': 'Gurubewilagama',
            '71352': 'Hakahinna',
            '71715': 'Hakbellawaka',
            '70145': 'Halpe',
            '70171': 'Halwinna',
            '70106': 'Handagiriya',
            '70164': 'Hapugastenna',
            '70105': 'Hatangala',
            '70108': 'Hatarabage',
            '71046': 'Helamada',
            '71530': 'Hemmatagama',
            '71210': 'Hettimulla',
            '71108': 'Hewadiwela',
            '70012': 'Hidellana',
            '71520': 'Hingula',
            '71417': 'Hinguralakanda',
            '70296': 'Hiramadagama',
            '71014': 'Hiriwadunna',
            '70144': 'Ihalagama',
            '71313': 'Imbulana',
            '71055': 'Imbulgasdeniya',
            '70342': 'Ittakanda',
            '71202': 'Kabagamuwa',
            '70016': 'Kahangama',
            '70150': 'Kahawatta',
            '70450': 'Kalawana',
            '70122': 'Kaltota',
            '71372': 'Kannattota',
            '70488': 'Karandana',
            '70018': 'Karangoda',
            '71000': 'Kegalle',
            '71533': 'Kehelpannala',
            '70352': 'Kella Junction',
            '70480': 'Kiriella',
            '71720': 'Kitulgala',
            '70180': 'Kolambageara',
            '70403': 'Kolombugama',
            '70350': 'Kolonna',
            '71501': 'Kondeniya',
            '71370': 'Kotiyakumbura',
            '70005': 'Kudawa',
            '70500': 'Kuruwita',
            '70056': 'Lellopitiya',
            '71315': 'Lewangama',
            '70134': 'Lmbulpe',
            '70158': 'Madalagama',
            '71722': 'Mahabage',
            '71063': 'Mahapallegama',
            '71211': 'Maharangalla',
            '70112': 'Mahawalatenna',
            '70298': 'Makandura Sabara',
            '71507': 'Makehelwala',
            '71704': 'Malalpola',
            '71411': 'Maliboda',
            '71325': 'Malmaduwa',
            '70001': 'Malwala Junction',
            '70041': 'Marapana',
            '70482': 'Matuwagalagama',
            '71500': 'Mawanella',
            '70021': 'Medagalatur',
            '70127': 'Meddekanda',
            '71716': 'Migastenna Sabara',
            '70494': 'Minipura Dumbara',
            '70604': 'Mitipola',
            '71432': 'Miyanawita',
            '71016': 'Molagoda',
            '70129': 'Morahela',
            '71220': 'Morontota',
            '70212': 'Mulendiyawala',
            '70117': 'Mulgama',
            '70469': 'Nawalakanda',
            '70165': 'Nawinnapinnakanda',
            '71060': 'Nelundeniya',
            '70038': 'Niralagama',
            '70400': 'Nivitigala',
            '71602': 'Niyadurupola',
            '71407': 'Noori',
            '70215': 'Omalpe',
            '70080': 'Opanayaka',
            '70230': 'Padalangala',
            '70170': 'Pallebedda',
            '70133': 'Pambagolla',
            '70218': 'Panamura',
            '70152': 'Panapitiya',
            '70461': 'Panapola',
            '70612': 'Panawala',
            '70550': 'Parakaduwa',
            '71105': 'Parape',
            '71130': 'Pattampitiya',
            '70045': 'Pebotuwa',
            '70070': 'Pelmadulla',
            '70472': 'Pimbura',
            '70130': 'Pinnawala',
            '71360': 'Pitagaldeniya',
            '71039': 'Pothukoladeniya',
            '70338': 'Pothupitiya',
            '70116': 'Rajawaka',
            '70300': 'Rakwana',
            '71100': 'Rambukkana',
            '70162': 'Ranwala',
            '70135': 'Rassagala',
            '70036': 'Ratna Hangamuwa',
            '70000': 'Ratnapura',
            '71300': 'Ruwanwella',
            '70142': 'Samanalawewa',
            '71708': 'Seaforth Colony',
            '70004': 'Sri Palabaddala',
            '70502': 'Sudagala',
            '70101': 'Talakolahinna',
            '71541': 'Talgaspitiya',
            '70118': 'Tanjantenna',
            '71724': 'Teligama',
            '71619': 'Tholangamuwa',
            '71106': 'Thotawella',
            '71610': 'Tulhiriya',
            '70205': 'Tunkama',
            '71062': 'Tuntota',
            '71113': 'Udagaldeniya',
            '70154': 'Udaha Hawupe',
            '70044': 'Udakarawita',
            '70034': 'Udaniriella',
            '71236': 'Udapotha',
            '70190': 'Udawalawe',
            '71521': 'Udumulla',
            '70345': 'Ullinduwawa',
            '71200': 'Undugoda',
            '71510': 'Ussapitiya',
            '70459': 'Veddagala',
            '70348': 'Vijeriya',
            '71303': 'Wahakula',
            '71304': 'Waharaka',
            '70138': 'Waleboda',
            '71600': 'Warakapola',
            '70408': 'Watapotha',
            '71035': 'Watura',
            '70456': 'Waturawa',
            '71702': 'Weeoya',
            '71234': 'Wegalla',
            '70104': 'Welihelatenna',
            '71712': 'Welipathayaya',
            '71622': 'Weragala',
            '70066': 'Wewelwatta',
            '70114': 'Wikiliya',
            '71116': 'Yatagama',
            '71326': 'Yatapana',
            '71700': 'Yatiyantota',
            '71029': 'Yattogoda',
            //North Western Province Post Codes: Sri Lanka
            '61012': 'Adippala',
            '60416': 'Alahengama',
            '60182': 'Alahitiyawa',
            '60047': 'Alawatuwala',
            '60280': 'Alawwa',
            '61024': 'Ambakandawila',
            '60036': 'Ambakote',
            '60650': 'Ambanpola',
            '61500': 'Anamaduwa',
            '61508': 'Andigama',
            '61264': 'Angunawila',
            '60074': 'Anhandiya',
            '60214': 'Anukkane',
            '60308': 'Aragoda',
            '60706': 'Ataragalla',
            '61328': 'Attawilluwa',
            '60462': 'Awulegama',
            '60604': 'Balalla',
            '60347': 'Bamunukotuwa',
            '60424': 'Bandara Koswatta',
            '61238': 'Bangadeniya',
            '61262': 'Baranankattuwa',
            '61246': 'Battuluoya',
            '60450': 'Bingiriya',
            '60107': 'Bogamulla',
            '60155': 'Bopitiya',
            '60437': 'Boraluwewa',
            '60027': 'Boyagane',
            '61136': 'Bujjampola',
            '60291': 'Bujjomuwa',
            '60076': 'Buluwala',
            '61000': 'Chilaw',
            '60130': 'Dambadeniya',
            '61130': 'Dankotuwa',
            '60174': 'Daraluwa',
            '60228': 'Deegalla',
            '60044': 'Delwite',
            '60024': 'Demataluwa',
            '60544': 'Diddeniya',
            '60485': 'Digannewa',
            '60472': 'Divullegoda',
            '60530': 'Dodangaslanda',
            '60013': 'Doratiyawa',
            '60260': 'Dummalasuriya',
            '61192': 'Dunkannawa',
            '60716': 'Ehetuwewa',
            '60156': 'Elibichchiya',
            '61308': 'Eluwankulama',
            '60718': 'Embogama',
            '61343': 'Ettale',
            '60266': 'Etungahakotuwa',
            '60700': 'Galgamuwa',
            '60712': 'Gallewa',
            '61233': 'Galmuruwa',
            '60752': 'Girathalana',
            '60140': 'Giriulla',
            '60522': 'Gokaralla',
            '60170': 'Gonawila',
            '60441': 'Halmillawewa',
            '60414': 'Hengamuwa',
            '60430': 'Hettipola',
            '60486': 'Hilogama',
            '60034': 'Hindagolla',
            '60546': 'Hiriyala Lenawa',
            '60458': 'Hiruwalpola',
            '60181': 'Horambawa',
            '60474': 'Hulogedara',
            '60477': 'Hulugalla',
            '60582': 'Hunupola',
            '60211': 'Ihala Gomugomuwa',
            '60135': 'Ihala Katugampala',
            '61154': 'Ihala Kottaramulla',
            '61316': 'Ihala Puliyankulama',
            '60188': 'Ilippadeniya',
            '60016': 'Indulgodakanda',
            '61514': 'Inginimitiya',
            '60064': 'Inguruwatta',
            '60045': 'Iriyagolla',
            '60053': 'Ismailpuram',
            '60025': 'Ithanawatta',
            '60492': 'Kadigawa',
            '60062': 'Kahapathwala',
            '61236': 'Kakkapalliya',
            '61534': 'Kalladiya',
            '61360': 'Kalpitiya',
            '60096': 'Kalugamuwa',
            '60054': 'Kanadeniyawala',
            '60422': 'Kanattewewa',
            '61358': 'Kandakuliya',
            '60106': 'Karagahagedara',
            '60602': 'Karambe',
            '61022': 'Karativponparappi',
            '61307': 'Karawitagara',
            '61032': 'Karuwalagaswewa',
            '61180': 'Katuneriya',
            '60350': 'Katupota',
            '60183': 'Kekunagolla',
            '60288': 'Keppitiwalana',
            '60548': 'Kimbulwanaoya',
            '60184': 'Kirimetiyawa',
            '61362': 'Kirimundalama',
            '60212': 'Kirindawa',
            '60502': 'Kirindigalla',
            '60188': 'Kithalawa',
            '60410': 'Kobeigane',
            '60028': 'Kohilagedara',
            '60630': 'Konwewa',
            '60356': 'Kosdeniya',
            '60029': 'Kosgolla',
            '61158': 'Koswatta',
            '60483': 'Kotawehera',
            '61252': 'Kottantivu',
            '61532': 'Kottukachchiya',
            '60003': 'Kudagalgamuwa',
            '60754': 'Kudakatnoruwa',
            '61226': 'Kudawewa',
            '60200': 'Kuliyapitiya',
            '61014': 'Kumarakattuwa',
            '60508': 'Kumbukgeta',
            '60506': 'Kumbukwewa',
            '60430': 'Kuratihena',
            '61356': 'Kuruketiyawa',
            '60000': 'Kurunegala',
            '60162': 'Labbala',
            '60500': 'Lbbagamuwa',
            '60238': 'Lhala Kadigamuwa',
            '61138': 'Lihiriyagama',
            '60232': 'Llukhena',
            '60108': 'Lonahettiya',
            '61150': 'Lunuwila',
            '60552': 'Madahapola',
            '60209': 'Madakumburumulla',
            '61230': 'Madampe',
            '60532': 'Maduragoda',
            '61270': 'Madurankuliya',
            '60512': 'Maeliya',
            '60221': 'Magulagama',
            '60731': 'Mahagalkadawala',
            '60479': 'Mahagirilla',
            '61272': 'Mahakumbukkadawala',
            '60516': 'Mahamukalanyaya',
            '60724': 'Mahananneriya',
            '60286': 'Mahauswewa',
            '61512': 'Mahawewa',
            '60600': 'Maho',
            '60714': 'Makulewa',
            '60514': 'Makulpotha',
            '60578': 'Makulwewa',
            '60404': 'Malagane',
            '61341': 'Mampuri',
            '60434': 'Mandapola',
            '61266': 'Mangalaeliya',
            '61210': 'Marawila',
            '60344': 'Maspotha',
            '60060': 'Mawathagama',
            '60612': 'Medivawa',
            '60750': 'Meegalawa',
            '60066': 'Meetanwala',
            '60484': 'Meewellawa',
            '60540': 'Melsiripura',
            '60304': 'Metikumbura',
            '60121': 'Metiyagane',
            '60004': 'Minhettiya',
            '60406': 'Minuwangete',
            '60408': 'Mirihanagama',
            '60495': 'Monnekulama',
            '60354': 'Moragane',
            '60640': 'Moragollagama',
            '60038': 'Morathiha',
            '61506': 'Mudalakkuliya',
            '61014': 'Mugunuwatawana',
            '61274': 'Mukkutoduwawa',
            '60218': 'Munamaldeniya',
            '61250': 'Mundel',
            '60122': 'Muruthenge',
            '61195': 'Muttibendiwila',
            '60482': 'Nabadewa',
            '60590': 'Nagollagama',
            '60226': 'Nagollagoda',
            '61120': 'Nainamadama',
            '60186': 'Nakkawatta',
            '61244': 'Nalladarankattuwa',
            '60100': 'Narammala',
            '60152': 'Narangoda',
            '61190': 'Nattandiya',
            '61520': 'Nawagattegama',
            '60292': 'Nawatalwatta',
            '60549': 'Nelliya',
            '60580': 'Nikadalupotha',
            '60470': 'Nikaweratiya',
            '61342': 'Norachcholai',
            '60461': 'Padeniya',
            '60236': 'Padiwela',
            '60062': 'Pahalagiribawa',
            '60735': 'Pahamune',
            '61280': 'Palaviya',
            '61040': 'Pallama',
            '61354': 'Palliwasalturai',
            '60704': 'Panadaragama',
            '60348': 'Panagamuwa',
            '60052': 'Panaliya',
            '60312': 'Panirendawa',
            '60558': 'Panliyadda',
            '60160': 'Pannala',
            '60554': 'Pansiyagama',
            '60518': 'Periyakadneluwa',
            '60439': 'Pihimbiya Ratmale',
            '60053': 'Pihimbuwa',
            '60058': 'Pilessa',
            '60300': 'Polgahawela',
            '60620': 'Polpitigama',
            '60330': 'Pothuhera',
            '61162': 'Pothuwatawana',
            '60072': 'Puswelitenna',
            '61300': 'Puttalam',
            '61326': 'Puttalam Cement Factory',
            '61242': 'Rajakadaluwa',
            '60606': 'Ridibendiella',
            '60040': 'Ridigama',
            '60736': 'Saliya Asokapura',
            '61324': 'Saliyawewa Junction',
            '60176': 'Sandalankawa',
            '61042': 'Serukele',
            '61312': 'Sirambiadiya',
            '60478': 'Sirisetagama',
            '61504': 'Siyambalagashene',
            '60646': 'Siyambalangamuwa',
            '60737': 'Solepura',
            '60738': 'Solewewa',
            '60436': 'Sunandapura',
            '61322': 'Tabbowa',
            '60306': 'Talawattegedara',
            '61344': 'Talawila Church',
            '60734': 'Tambutta',
            '60208': 'Thalahitimulla',
            '60624': 'Thalakolawewa',
            '60572': 'Thalwita',
            '60584': 'Thambagalla',
            '60227': 'Thimbiriyawa',
            '60476': 'Tisogama',
            '61224': 'Toduwawa',
            '60499': 'Torayaya',
            '60426': 'Tuttiripitigama',
            '61004': 'Udappuwa',
            '60250': 'Udubaddawa',
            '60094': 'Uhumiya',
            '60622': 'Ulpotha Pallekele',
            '61502': 'Uridyawa',
            '60732': 'Usgala Siyabmalangamuwa',
            '61306': 'Vanathawilluwa',
            '60318': 'Wadakada',
            '60204': 'Wadumunnegedara',
            '61110': 'Waikkal',
            '60198': 'Walakumburumulla',
            '60465': 'Wannigama',
            '60721': 'Wannikudawewa',
            '60722': 'Wannilhalagama',
            '60490': 'Wannirasnayakapura',
            '60739': 'Warawewa',
            '60400': 'Wariyapola',
            '61198': 'Watugahamulla',
            '60262': 'Watuwatta',
            '60454': 'Weerapokuna',
            '60464': 'Welawa Juncton',
            '60240': 'Welipennagahamulla',
            '60402': 'Wellagala',
            '60456': 'Wellarawa',
            '60570': 'Wellawa',
            '60206': 'Welpalla',
            '61170': 'Wennappuwa',
            '60284': 'Wennoruwa',
            '60080': 'Weuda',
            '60195': 'Wewagama',
            '61006': 'Wijeyakatupotha',
            '61008': 'Wilpotha',
            '60202': 'Yakwila',
            '60314': 'Yatigaloluwa',
            '61144': 'Yogiyana',
            //Southern Province Post Codes: Sri Lanka
            '80212': 'Agaliya',
            '80650': 'Ahangama',
            '80562': 'Ahungalla',
            '80090': 'Akmeemana',
            '81400': 'Akuressa',
            '81475': 'Alapaladeniya',
            '80332': 'Aluthwala',
            '80300': 'Ambalangoda',
            '82100': 'Ambalantota',
            '80204': 'Ampegama',
            '80422': 'Amugoda',
            '80044': 'Anangoda',
            '80122': 'Angulugaha',
            '82220': 'Angunakolapelessa',
            '80048': 'Ankokkawala',
            '81032': 'Aparekka',
            '81402': 'Athuraliya',
            '80200': 'Baddegama',
            '80550': 'Balapitiya',
            '80143': 'Banagala',
            '82005': 'Bandagiriya Colony',
            '82110': 'Barawakumbuka',
            '80320': 'Batapola',
            '82400': 'Beliatta',
            '81614': 'Bengamuwa',
            '80500': 'Bentota',
            '82102': 'Beragama',
            '81541': 'Beralapanathara',
            '82618': 'Beralihela',
            '80270': 'Boossa',
            '81412': 'Bopagoda',
            '82458': 'Bowalagama',
            '82002': 'Bundala',
            '81612': 'Dampahala',
            '81452': 'Deegala Lenama',
            '81320': 'Deiyandara',
            '81477': 'Dellawa',
            '81314': 'Denagama',
            '81730': 'Denipitiya',
            '81500': 'Deniyaya',
            '81454': 'Derangala',
            '81160': 'Devinuwara',
            '80654': 'Dikkumbura',
            '81200': 'Dikwella',
            '81038': 'Diyagaha',
            '81422': 'Diyalape',
            '80250': 'Dodanduwa',
            '80402': 'Ella Tanabaddegama',
            '82619': 'Ellagala',
            '80400': 'Elpitiya',
            '80458': 'Ethkandura',
            '80000': 'Galle',
            '81170': 'Gandara',
            '80440': 'Ganegoda',
            '82586': 'Gangulandeniya',
            '82420': 'Getamanna',
            '80220': 'Ginimellagaha',
            '80280': 'Gintota',
            '82401': 'Goda Koggalla',
            '80302': 'Godahena',
            '81408': 'Godapitiya',
            '81072': 'Gomilamawarala',
            '80502': 'Gonagalpura',
            '82602': 'Gonagamuwa Uduwila',
            '80054': 'Gonamulla Junction',
            '80230': 'Gonapinuwala',
            '82006': 'Gonnoruwa',
            '80630': 'Habaraduwa',
            '80506': 'Haburugala',
            '81300': 'Hakmana',
            '82248': 'Hakuruwela',
            '80146': 'Halvitigala Colony',
            '82000': 'Hambantota',
            '81326': 'Handugala',
            '80132': 'Hawpe',
            '80240': 'Hikkaduwa',
            '80080': 'Hiniduma',
            '80056': 'Hiyare',
            '81108': 'Horapawita',
            '82456': 'Horewelagoda',
            '82120': 'Hungama',
            '82412': 'Ihala Beligalla',
            '80134': 'Ihala Walpola',
            '82462': 'Ittademaliya',
            '82252': 'Julampitiya',
            '80460': 'Kahaduwa',
            '82126': 'Kahandamodara',
            '80312': 'Kahawa',
            '81478': 'Kalubowitiyana',
            '81750': 'Kamburugamuwa',
            '81100': 'Kamburupitiya',
            '80136': 'Kananke Bazaar',
            '80151': 'Karagoda',
            '81082': 'Karagoda Uyangoda',
            '80360': 'Karandeniya',
            '81106': 'Karaputugala',
            '81318': 'Karatota',
            '82274': 'Kariyamaditta',
            '82500': 'Katuwana',
            '82622': 'Kawantissapura',
            '81020': 'Kekanadurra',
            '82550': 'Kirama',
            '82614': 'Kirinda',
            '81514': 'Kiriweldola',
            '81456': 'Kiriwelkele',
            '81522': 'Kolawenigama',
            '80570': 'Kosgoda',
            '81480': 'Kotapola',
            '80062': 'Kottawagama',
            '81180': 'Kottegoda',
            '80328': 'Kuleegoda',
            '81526': 'Lankagama',
            '80432': 'Lhalahewessa',
            '80130': 'Lmaduwa',
            '80510': 'Lnduruwa',
            '82108': 'Lunama',
            '82634': 'Lunugamwehera',
            '82608': 'Magama',
            '80152': 'Magedara',
            '82016': 'Mahagalwewa',
            '81070': 'Makandura',
            '80144': 'Maliduwa',
            '82109': 'Mamadala',
            '80112': 'Mapalagama',
            '80116': 'Mapalagama Central',
            '81416': 'Maramba',
            '81000': 'Matara',
            '80424': 'Mattaka',
            '80092': 'Meda-Keembiya',
            '82254': 'Medamulana',
            '81524': 'Mediripitiya',
            '80330': 'Meetiyagoda',
            '82270': 'Middeniya',
            '81312': 'Miella',
            '82014': 'Migahajandur',
            '81740': 'Mirissa',
            '80508': 'Miriswatta',
            '82416': 'Modarawana',
            '81532': 'Moragala Kirillapone',
            '81470': 'Morawaka',
            '81071': 'Mulatiyana Junction',
            '82242': 'Mulkirigala',
            '81092': 'Nadugala',
            '80110': 'Nagoda',
            '81017': 'Naimana',
            '80064': 'Nakiyadeniya',
            '81302': 'Narawelpita',
            '80416': 'Nawadagala',
            '80082': 'Neluwa',
            '82135': 'Netolpitiya',
            '82414': 'Nihiluwa',
            '80318': 'Nindana',
            '80142': 'Opatha',
            '82636': 'Padawkema',
            '82008': 'Pahala Andarawewa',
            '81472': 'Pahala Millawa',
            '81050': 'Palatuwa',
            '82454': 'Pallekanda',
            '80075': 'Panangala',
            '80086': 'Pannimulla Panagoda',
            '81474': 'Paragala',
            '80114': 'Parana Thanayamgoda',
            '81322': 'Parapamulla',
            '81615': 'Pasgoda',
            '81722': 'Penetiyana',
            '81450': 'Pitabeddara',
            '80420': 'Pitigala',
            '80170': 'Poddala',
            '80408': 'Porawagama',
            '81538': 'Pothdeniya',
            '81290': 'Puhulwella',
            '81316': 'Radawela',
            '82554': 'Rammalawarapitiya',
            '82612': 'Ranakeliya',
            '82018': 'Ranmuduwewa',
            '82125': 'Ranna',
            '81064': 'Ransegoda',
            '80354': 'Rantotuwila',
            '80260': 'Ratgama',
            '81030': 'Ratmale',
            '82276': 'Ratmalwala',
            '81074': 'Rotumba',
            '82106': 'Ru/Ridiyagama',
            '81462': 'Siyambalagoda',
            '82010': 'Sooriyawewa Town',
            '81051': 'Sultanagoda',
            '80058': 'Talagampola',
            '80406': 'Talgaspe',
            '80470': 'Talgaswela',
            '80615': 'Talpe',
            '82200': 'Tangalla',
            '80148': 'Tawalama',
            '81060': 'Telijjawila',
            '81280': 'Thihagoda',
            '80244': 'Tiranagama',
            '82600': 'Tissamaharama',
            '82504': 'Uda Gomadiya',
            '80108': 'Udalamatta',
            '82638': 'Udamattala',
            '80070': 'Udugama',
            '80168': 'Uluvitike',
            '80600': 'Unawatuna',
            '80214': 'Unenwitiya',
            '80352': 'Uragaha',
            '80350': 'Uragasmanhandiya',
            '81600': 'Urubokka',
            '81230': 'Urugamuwa',
            '81414': 'Urumutta',
            '82278': 'Uswewa',
            '80150': 'Viharahena',
            '82232': 'Vitharandeniya',
            '80042': 'Wakwella',
            '80046': 'Walahanduwa',
            '81294': 'Walakanda',
            '81404': 'Wilpita',
            '80150': 'Yakkalamulla',
            '80107': 'Yatalamatta',
            '82418': 'Yatigala',
            '81034': 'Yatiyana',
            // Add more postal code mappings as needed
        };

        // Listen for changes in the postal code input
        $('#postal').on('input', function() {
            var enteredPostalCode = $(this).val();
            var correspondingCity = postalCodeMapping[enteredPostalCode];

            // Update the city input if a corresponding city is found
            if (correspondingCity) {
                $('#city').val(correspondingCity);
            }
        });
    });
</script>

<script>
    // Function to toggle the checkbox based on the response
    function toggleCheckboxVisibility(addressExists) {
        if (addressExists) {
            $('#addressCheckbox').show();
        } else {
            $('#addressCheckbox').hide();
        }
    }

    // AJAX request to check if the user's address exists
    $.ajax({
        type: "get",
        url: "/check-address",
        dataType: "json",
        success: function(response) {
            if (response.addressExists) {
                toggleCheckboxVisibility(true);
            } else {
                toggleCheckboxVisibility(false);
                $('#noAddressError').show(); // Show the error message for no existing address
            }
        },
        error: function() {
            // Handle the error if the AJAX request fails
            console.error("Error checking user's address.");
        }
    });

    // Add an event listener for the checkbox to allow selecting only one option
    $('#addressCheckboxinput').on('change', function() {
        if ($(this).is(':checked')) {
            // Hide other address fields when "Use My Shopping Address" is selected
            $('.txt_field:not(#addressCheckbox)').hide();
        } else {
            // Show other address fields when unchecked
            $('.txt_field:not(#addressCheckbox)').show();
            $('#noAddressError').hide(); // Hide the error message if shown
        }
    });

    // Add an event listener to handle form submission and data storage in the controller
    $('#proceedToCheckoutBtn').on('click', function() {
        // Collect data from form fields
        // Perform validation and proceed to checkout or display error messages
    });
</script>

<script>
    //your javascript goes here
    var currentTab = 0;
    document.addEventListener("DOMContentLoaded", function(event) {


        showTab(currentTab);

    });

    function showTab(n) {
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {

            document.getElementById("nextBtn").innerHTML = "Submit";

        } else {
            document.getElementById("nextBtn").innerHTML = "Add Shipping Address";
        }
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        var x = document.getElementsByClassName("tab");
        if (n == 1 && !validateForm()) return false;
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
            // document.getElementById("addressForm").submit();
            // return false;
            //alert("sdf");
            document.getElementById("nextprevious").style.display = "none";
            document.getElementById("all-steps").style.display = "none";





        }
        showTab(currentTab);
    }

    function validateForm() {
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        for (i = 0; i < y.length; i++) {
            if (y[i].value == "") {
                y[i].className += " invalid";
                valid = false;
            }
        }
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid;
    }

    function fixStepIndicator(n) {
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        x[n].className += " active";
    }
</script>
<script>
    // Update the AJAX success function for checking user address
    $.ajax({
        // ... your existing AJAX setup
        success: function(response) {
            if (response.addressExists) {
                toggleCheckboxVisibility(true);
            } else {
                toggleCheckboxVisibility(false);
                $('#noAddressError').show(); // Show the error message for no existing address
            }
        },
        // ... other AJAX configurations
    });

    // Add an event listener for the checkbox to allow selecting only one option
    $('#addressCheckboxinput').on('change', function() {
        if ($(this).is(':checked')) {
            // Hide other address fields when "Use My Shopping Address" is selected
            $('.txt_field:not(#addressCheckbox)').hide();
        } else {
            // Show other address fields when unchecked
            $('.txt_field:not(#addressCheckbox)').show();
            $('#noAddressError').hide(); // Hide the error message if shown
        }
    });

    // Add an event listener to handle form submission and data storage in the controller
    $('#cartcheckout_btn .proceed').on('click', function() {
        // Collect data from form fields
        var address = $('#address1').val();
        var postal = $('#postal').val();
        var city = $('#city').val();
        var country = $('#country').val();

        // Add AJAX call to send the form data to the controller endpoint for storage
        $.ajax({
            type: 'post',
            url: '/store-address', // Replace with your endpoint
            data: {
                address: address,
                postal: postal,
                city: city,
                country: country,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirect to /confirm-checkout route upon successful address storage
                    window.location.href = '/confirm-checkout';
                } else {
                    // Handle errors or display messages accordingly
                    alert('Failed to store address. Please try again.');
                }
            }
        });
    });
</script>
<script>
    $("#shippingaddressform").submit(function() {
        $.ajax({
            type: "post",
            url: "/account-update",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data) {
                if (data.error == 0) {
                    toastr.success(data.msg, "Success");
                } else {
                    toastr.error(data.msg, "Error");
                }
            }
        });
    });
</script>
@endsection
