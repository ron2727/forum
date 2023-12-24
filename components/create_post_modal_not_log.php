<style>
    .lbl-create-1 {
        font-size: 1.1rem;
    }

    .lbl-create-2,
    .lbl-create-3 {
        font-size: 0.7rem;
    }

    .login-link {
        font-size: 0.8rem;
    }

    @media (min-width: 768px) {
        .lbl-create-1 {
            font-size: 1.3rem;
        }

        .lbl-create-2,
        .lbl-create-3 {
            font-size: 0.9rem;
        }

        .login-link {
            font-size: 1rem;
        }
    }

    @media (min-width: 1024px) {}
</style>
<div class="modal fade" id="postModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <h4 class="lbl-create-1">Create Account</h4>
                <h6 class="lbl-create-2 text-secondary">Please create account first to this action</h6>
                <a href="../../myproject/forum/signup.php" class="login-link nav-link w-75 rounded-5 px-5 py-2 text-center text-white fw-semibold my-3" style="background:#6d28d9">Create Account</a>
                <p class="lbl-create-3">Have an account? <a href="../../myproject/forum/login.php" class=" text-decoration-none" style="color: #6d28d9;">Login</a></p>
            </div>
        </div>
    </div>
</div>