<h1>Invoice</h1>

<table>
  <tr>
    <th>Project</th>
    <th>Hours</th>
    <th>Amount</th>
  </tr>

  @foreach($entries as $e)
  <tr>
    <td>{{ $e->project->name }}</td>
    <td>{{ round($e->duration / 3600, 2) }}</td>
    <td>
      ${{ round(($e->duration / 3600) * $e->project->hourly_rate, 2) }}
    </td>
  </tr>
  @endforeach
</table>

<h2>Total: ${{ round($total, 2) }}</h2>