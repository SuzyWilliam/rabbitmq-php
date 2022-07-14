 still were able to send messages to queues. 
 That was possible because we were using a default exchange, which we identify by the empty string
 
        $channel->basic_publish($msg, '', 'hello');