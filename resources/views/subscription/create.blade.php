<form action="{{ url('/subscriptions') }}" method="POST">
    @csrf
    <label for="plan">Select Plan:</label>
    <select name="plan" id="plan">
        <option value="3_months">3 Months</option>
        <option value="6_months">6 Months</option>
        <option value="9_months">9 Months</option>
    </select>
    <button type="submit">Start Subscription</button>
</form>
