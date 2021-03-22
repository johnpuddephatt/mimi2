<div class="editor-js-block editor-js-block__table">
  <table>
  @foreach($data['content'] as $row)
    <tr>
      @foreach($row as $index => $value)
        <td>
          {!! $value !!}
        </td>
      @endforeach
    </tr>
  @endforeach
  </table>
</div>
