<x-layout.default>
    <div class="containermx-auto p-3">
        <div class="bg-white rounded flex items-center justify-center">
            <div class="grid grid-cols-1 lg:grid-cols-4 mb-4 p-3 gap-6">
                <div class="bg-blue-100 rounded">
                    <label for="printing" name="printing" id="printing">Printing</label>
                    <select name="service" id="service">
                        @foreach($service as $key => $layanan)
                            <option value="{{  }}"></option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>
