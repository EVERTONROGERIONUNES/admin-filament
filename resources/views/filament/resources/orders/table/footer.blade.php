<td colspan="3" class="text-black">Total: </td>
<td class="text-black" colspan="1">{{ $this->getTableRecords()->sum('items_count') }}</td>
<td class="text-black" colspan="1">{{ money($this->getTableRecords()->sum('orderTotal'), 'BRL') }}</td>