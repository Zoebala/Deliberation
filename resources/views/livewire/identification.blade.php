{{-- <div> --}}
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="col-lg-6">
        <form wire:submit="save" class="php-email-form">
          <div class="row">
            <div class="col form-group">
              <input type="text" wire:model="name" name="name" class="form-control" id="name" placeholder="Your Name" required>
                {!! $errors->first("name",'<p class="text-danger fst-italic">:message</p>') !!}
            </div>
            <div class="col form-group">
              <input type="email" class="form-control" wire:model="email" name="email" id="email" placeholder="Your Email" required>
              {!! $errors->first("email",'<p class="text-danger fst-italic">:message</p>') !!}
            </div>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" wire:model="password" name="password" id="subject" placeholder="password" required>
            {!! $errors->first("password",'<p class="text-danger fst-italic">:message</p>') !!}
          </div>
          {{-- <div class="form-group">
            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
          </div> --}}
          <div class="my-3">
            <div class="loading" wire:loading>chargement...</div>
            <div class="message"></div>
            {{-- <div class="sent-message">Your message has been sent. Thank you!</div> --}}
          </div>
          <div class="text-center"><button type="submit"><i class="bi bi-plus-circle"></i> S'identifier</button></div>
          <a href="/admin/etudiants" ><i class="bi bi-login"></i> J'ai déjà un compte</a>
        </form>
      </div>
{{-- </div> --}}
