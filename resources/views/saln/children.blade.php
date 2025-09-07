{{-- resources/views/saln/children.blade.php --}}

<table class="table table-bordered saln-table mb-4">
    <thead class="table-light">
        <tr>
            <th colspan="3" class="text-center">
                UNMARRIED CHILDREN BELOW EIGHTEEN (18) YEARS OF AGE 
                LIVING IN DECLARANT’S HOUSEHOLD
            </th>
        </tr>
        <tr>
            <th style="width: 50%;">Children</th>
            <th style="width: 25%;">Date of Birth</th>
            <th style="width: 25%;">Age</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Filter children below 18 (age derived from birth_date)
            $eligibleChildren = $personalInfo->children->filter(function ($child) {
                return \Carbon\Carbon::parse($child->birth_date)->age < 18;
            });
        @endphp

        @forelse($eligibleChildren as $child)
            <tr>
                <td>
                    <input type="text" 
                           name="children[{{ $child->id }}][full_name]" 
                           value="{{ old('children.' . $child->id . '.full_name', $child->full_name) }}" 
                           class="form-control border-0"
                           readonly>
                </td>
                <td>
                    <input type="date" 
                           name="children[{{ $child->id }}][birth_date]" 
                           value="{{ old('children.' . $child->id . '.birth_date', $child->birth_date) }}" 
                           class="form-control border-0"
                           readonly>
                </td>
                <td>
                    <input type="text" 
                           value="{{ \Carbon\Carbon::parse($child->birth_date)->age }}" 
                           class="form-control border-0 text-center" 
                           readonly>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">
                    No unmarried children below 18 living in household.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
