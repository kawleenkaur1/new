<div class="row">
    <div id="flFormsGrid" class="col-lg-12 layout-spacing">
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{route('wallet_recharge',['id'=>$user->id])}}">

                    <div class="form-group">
                        <label for="name">Amount</label>
                        <input type="number" min="0"  step="0.01"  onkeyup="if(parseInt(this.value)<0){ this.value =0; return false; }"  value="{{ old('amount')}}" name="amount" class="form-control" placeholder="Enter Amount..<?=rz_currency()?> " required>
                    </div>

                    @csrf
                    <div class="form-group">
                        <label for="inputState">Txn Type</label>
                        <select id="inputState" name="txn_type" class="form-control" required>
                            <option value="">Choose...</option>
                            <option value="1" >Credit</option>
                            <option value="2" >Debit</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Message</label>
                        <textarea name="message" class="form-control"></textarea>
                    </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>
