<style>
body {
  font-family: Arial;
  font-size: 14px;
}

h1 {
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th, td {
  border-bottom: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background: #f5f5f5;
}
</style>

<h1>Invoice</h1>

<p>Date: {{ now()->format('Y-m-d') }}</p>

<table>
  <tr>
    <th>Project</th>
    <th>Hours</th>
    <th>Rate</th>
    <th>Amount</th>
  </tr>

  @foreach($entries as $e)
  <tr>
    <td>{{ $e->project->name }}</td>
    <td>{{ round($e->duration / 3600, 2) }}</td>
    <td>${{ $e->project->hourly_rate }}</td>
    <td>
      ${{ round(($e->duration / 3600) * $e->project->hourly_rate, 2) }}
    </td>
  </tr>
  @endforeach
</table>

<h2 style="text-align:right;">
  Total: ${{ round($total, 2) }}
</h2>