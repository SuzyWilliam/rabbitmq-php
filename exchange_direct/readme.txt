instead of broadcasts all messages to all consumers. 
We want to extend that to allow filtering messages based on their severity

We were using a fanout exchange, which doesn't give us much flexibility - it's only capable of mindless broadcasting.

We will use a direct exchange instead. 
The routing algorithm behind a direct exchange is simple - 
    a message goes to the queues whose binding key exactly matches the routing key of the message.

Multiple bindings

It is perfectly legal to bind multiple queues with the same binding key. 
In our example we could add a binding between X and Q1 with binding key black. 
In that case, the direct exchange will behave like fanout and will broadcast the message to all the matching queues.
A message with routing key black will be delivered to both Q1 and Q2.