
<div class="row">
    <div class="form-holder">
        <div class="form-content">
            <div class="form-items" >                       

                    <!-- Chat -->
                    <div class="messages">
                    <div class="left message">
                        
                        <div class="right message">
                        <p>{{ $question->question ?? ''}}</p>
                        </div>

                        <div class="left message"><p><pre>{{ $question->answer ?? ''}}</pre></p></div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
