

    <div style="position: absolute; bottom:0px; left:0px;width:100%;">
        <div class="col-md-12">
            <div  class="offset-5 col-6">

                <p class="text-end">Ainsi fait à Mbanza-Ngungu, le {{$date}}</p>
                <h4 class="text-end" >
                    Le chef de Section {{ $queries[0]->section }} <br> <br> <br>

                    <span style="text-decoration: underline;">{{ $queries[0]->chsection }}</span>

                </h4>
                <p class="offset-8">{{ $queries[0]->grade }}</p>
            </div>

        </div>
        <hr style="border:1px dashed black">
        <p class="text-center">

            site <a href="">www.IST.Com, Couriel: section@gmail.com, sciences@ist-mbanza.com</a> <br> <br>
            {{-- <span> Tél: </span> --}}
        </p>

        {{-- <p style=" width:20%; display:inline-block; position:relative:bottom:2px; margin-left:20px;">printed by
            <img style="position:relative; top:10px;" src="{{'images/logo.jpeg'}}" alt="logo" width="50" class="img-fluid">
        </p>
        <p class="fst-italic" style="margin-left:45%; display:inline-block;position: relative;bottom:4px; "> Fait à Mbanza-Ngungu le {{$date}}</p> --}}



    </div>

</body>
</html>
