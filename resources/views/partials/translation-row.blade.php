<tr id="translation-{{$translation['id']}}" translate data-key="{{$translation['key']}}"
    data-group="{{$translation['group']}}">
    <td width="30%">
        <div class="" data-translation="translation">
            <p> {{ $translation['key'] }}</p>
        </div>
    </td>
    <td width="70%">
        <table width="100%" class="add-translattion-table">
            <tbody>
            @foreach($translation["translations"] as $locale => $translationValue )
                @if($loop->index == 0)
                    <tr>
                        <td class="bg-gray-100-30"
                            width="25%"> {{$locale}}
                        </td>
                        <td class="text-red-500"
                            width="75%" contenteditable="true"
                            data-locale="{{$locale}}"
                        >  {{$translationValue}}</td>
                    </tr>
                @else
                    <tr>
                        <td class="german-trans"
                            width="25%">{{$locale}}</td>
                        <td class="text-gray-700"
                            width="75%" contenteditable="true"
                            data-locale="{{$locale}}">{{$translationValue}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </td>
</tr>
