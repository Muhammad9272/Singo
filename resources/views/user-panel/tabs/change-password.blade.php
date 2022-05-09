@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row">
    <div class="col-md-5 m-auto">
        <div class="from-group">
            <form action="{{ route('user.password.update') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="previous_password">
                        Previous Password
                    </label>
                    <input type="password" class="form-control" id="previous_password" name="previous_password" placeholder="Old Password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password" required>
                </div>

                <div class="text-center pt-4">
                    <button type="submit" class="btn btn-dark-purple btn-lg px-6">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
