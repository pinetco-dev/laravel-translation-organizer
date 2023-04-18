<tr class="divide-x divide-gray-300 focus:bg-gray-400"
    id="translation-{{$translation['id']}}" translate
    data-key="{{$translation['key']}}"
    data-group="{{$translation['group']}}">
    <td class="relative py-2 pl-4 pr-4 text-black group" width="30%">
        <div class="" data-translation="translation">
            <p> {{ $translation['key'] }}</p>
        </div>
    </td>
    <td width="70%">
        <table width="100%">
            <tbody class="bg-white divide-y divide-gray-300">
            @foreach($translation["translations"] as $locale => $translationValue )
                @if($loop->index == 0)
                    <tr class="divide-x divide-gray-300">
                        <td class="py-2 pl-4 pr-4 text-black bg-gray-100/30"
                            width="25%"> {{$locale}}
                        </td>
                        <td class="py-2 pl-4 pr-4 text-red-500"
                            width="75%" contenteditable="true"
                            data-locale="{{$locale}}"
                        >  {{$translationValue}}</td>
                    </tr>
                @else
                    <tr class="divide-x divide-gray-300">
                        <td class="py-2 pl-4 pr-4 text-green-700 bg-green-300"
                            width="25%">{{$locale}}</td>
                        <td class="py-2 pl-4 pr-4 text-gray-700"
                            width="75%" contenteditable="true"
                            data-locale="{{$locale}}">{{$translationValue}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </td>
</tr>
