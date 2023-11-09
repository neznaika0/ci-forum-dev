<?= $this->extend('master')  ?>

<?= $this->section('header') ?>
    <?= view('account/_header', ['user' => $user]) ?>
<?= $this->endSection() ?>


<?= $this->section('main')  ?>
    <?= view('account/_post-head', [
        'title' => 'Security',
        'subTitle' => 'Manage your account security settings.'
    ]) ?>

    <?= view('account/security/_logins', ['logins' => $logins, 'agent' => $agent]) ?>

    <!-- Warning Zone -->
    <div class="border border-warning rounded-lg p-4 mt-12">
        <?= view('account/security/_change_password', ['validator' => $validator]) ?>

        <!-- Logout of all devices -->
        <div class="flex justify-between border-b pb-4 border-slate-700 my-4">
            <!-- Password reset button and description -->
            <div>
                <h3 class="font-bold">Log Out of All Devices</h3>
                <p class="text-sm opacity-50">
                    Log out of all devices, including this one.
                </p>
            </div>
            <div class="flex align-middle h-full">
                <button class="btn btn-outline btn-warning" <?= !$isRemembered ? 'disabled' : '' ?>
                    hx-post="<?= url_to('account-security-logout-all') ?>"
                    hx-confirm="Are you sure you want to log out of all devices?"
                >
                    Log Out All
                </button>
            </div>
        </div>

        <?= view('account/security/_two_factor_auth_email', ['user' => $user, 'validator' => $validator]) ?>
    </div>

    <!-- Danger Zone -->
    <div class="border border-error rounded-lg p-4 mt-12">
        <?= view('account/security/_delete') ?>
    </div>
<?= $this->endSection() ?>


<?= $this->section('sidebar')  ?>
    <?= view('account/_sidebar') ?>
<?= $this->endSection() ?>
