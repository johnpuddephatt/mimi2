@extends('layouts.static')

@section('content')
  <section class="section is-medium">

  <div class="container">
    <h2 class="title has-text-centered">Book a speaking club session<h2>
      <div class="columns is-desktop is-centered">
        <div class="column is-half content">
          <p>Practice speaking Italian in live zoom conversation lessons. Once you've reserved your place, we'll send you an email with all the details such as how to join and how to prepare (don't forget to check your junk mail just in case we get lost in there).</p>

            <details>
              <summary>
                What level should I choose?
              </summary>
              <ul>
                <li>From zero: I've never studied Italian before, or I would feel more comfortable with a group of 100% beginners. We'll be starting from ciao.</li>
                <li>Level 1: I know quite a lot of words, but I can't quite put them into sentences yet and have long pauses in between each word.</li>
                <li>Level 2: I can make myself understood when talking about simple topics. As soon as I try to say anything beyond the basics, I get mind blanks.</li>
                <li>Level 3: I can speak and understand fairly comfortably when using basic tenses, but I often have problems with more complex grammar and vocabulary.</li>
                <li>Level 4: I can already speak Italian. I want to learn to speak faster, make fewer mistakes and sound more natural.</li>
              </ul>
            </details>

        </div>
      </div>
    <iframe src="https://app.acuityscheduling.com/schedule.php?owner=21242679&amp;template=class" title="Schedule Appointment" width="100%" height="1600" frameborder="0" style="max-height: none; max-width: 100%; width: 1800px; overflow: hidden; height: 3444px !important;"></iframe>
  </div>
</section>
@endsection
