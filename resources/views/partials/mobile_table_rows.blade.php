@foreach ($mobiles as $key)
<tr>
    <td>{{ \Carbon\Carbon::parse($key->created_at)->format('Y-m-d / h:i') }}</td>
    <td>{{ $key->creator->name }}</td>
    <td>{{ $key->mobile_name }}</td>
    <td>{{ $key->company->name ?? 'N/A' }}</td>
    <td>{{ $key->group->name ?? 'N/A' }}</td>
    <td>{{ $key->vendor->name ?? 'N/A' }}</td>
    <td>{{ $key->imei_number }}</td>
    <td>{{ $key->sim_lock }}</td>
    <td>{{ $key->color }}</td>
    <td>{{ $key->storage }}</td>
    <td>{{ $key->battery_health }}</td>
    <td>{{ $key->cost_price }}</td>
    <td>{{ $key->selling_price }}</td>
    <td>
        <a href="{{ route('showHistory', $key->id) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-eye"></i>
        </a>
    </td>
    <td>
        <a href="" onclick="sold({{ $key->id }})" data-toggle="modal" data-target="#exampleModal3">
            <span class="badge badge-success">{{ $key->availability }}</span>
        </a>
    </td>
    <td>
        <a href="" onclick="transfer({{ $key->id }})" data-toggle="modal" data-target="#exampleModal2">
            <i class="fa fa-exchange" style="font-size: 20px"></i>
        </a>
    </td>
    <td>
        <a href="" onclick="edit({{ $key->id }})" data-toggle="modal" data-target="#exampleModal1">
            <i class="feather icon-edit"></i>
        </a> |
        <a href="" onclick="deletefn({{ $key->id }})" data-toggle="modal" data-target="#exampleModal4">
            <i style="color:red" class="feather icon-trash"></i>
        </a>
    </td>
</tr>
@endforeach
