<div class="checkout-wrapper">
    <h2>Payment form</h2>
    <form action="">
        <!-- Account Information -->
        <h4>Account Information</h4>
        <div class="input_group">
            <div class="input-box">
                <input type="text" placeholder="Full Name" required class="name">
                <i class="fa fa-user icon"></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Name on Card" required class="name">
                <i class="fa fa-user icon"></i>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="email" placeholder="Email Address" required class="name">
                <i class="fa fa-envelope icon"></i>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="text" placeholder="Address" required class="name">
                <i class="fa fa-map-marker icon" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="text" placeholder="City" required class="name">
                <i class="fas fa-university icon"></i>
            </div>
        </div>
        <!-- End Account information -->

        <!-- DOB and Gender -->

        <div class="input_group">
            <div class="input-box">
                <h4>Date of birth</h4>
                <input type="text" placeholder="DD" required class="dob">
                <input type="text" placeholder="MM" required class="dob">
                <input type="text" placeholder="YYYY" required class="dob">
            </div>
            <div class="input-box">
                <h4>Gender</h4>
                <input type="radio" name="gender" required id="b1" checked class="radio">
                <label for="b1">Male</label>
                <input type="radio" name="gender" required id="b2" class="radio">
                <label for="b2">Famale</label>
            </div>
        </div>
        <!-- End DOB and Gender -->

        <!-- Payment Detail start -->
        <div class="input_group">
            <div class="input-box">
                <h4>Payment details</h4>
                <input type="radio" name="pay" required id="bc1" class="radio" checked>
                <label for="bc1"><span><i class="fab fa-cc-visa"></i>Credit Card</span></label>
                <input type="radio" name="pay" required id="bc2" class="radio">
                <label for="bc2"><span><i class="fab fa-cc-paypal"></i>Paypal</span></label>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="tel" class="name" placeholder="Card number 1111-2222-3333-4444" required>
                <i class="fa fa-credit-card icon"></i>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="tel" class="name" placeholder="Card CCV 632" required>
                <i class="fa fa-user icon"></i>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="number" class="name" placeholder="Exp month" required>
                <i class="fa fa-calendar icon"></i>
            </div>
            <div class="input-box">
                <input type="number" class="name" placeholder="Exp Year" required>
                <i class="fa fa-calendar-o icon"></i>
            </div>
        </div>
        <div class="input_group">
            <div class="input-box">
                <input type="number" class="name" placeholder="Enter Amount" required>
                <i class="fas fa-money-check-alt icon"></i>
            </div>
        </div>

        <div class="input_group">
            <div class="input-box">
                <button type="submit">Pay Now</button>
            </div>
        </div>
        <!-- Payment Details End -->
    </form>
</div>