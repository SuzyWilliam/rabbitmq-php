# Learning RabbitMQ
RabbitMQ is one of the most popular open-source message brokers in use today. This lightweight software can help companies that have adopted a microservices model stitch together and enable communication between their services. In this course, join instructor Peter Morlion as he demonstrates how to properly install and work with RabbitMQ as a developer. After delving into the fundamental concepts behind message-based systems and the AMQP message protocol, Peter explains why you might want to use RabbitMQ, as well as how to install it. He then showcases how to implement the tool, covering both basic usage, such as how to publish to and consume from a RabbitMQ exchange, as well as more advanced topics like setting up and using authentication in RabbitM

**Learning objectives**
  * How message-based systems are used
  * The AMQP protocol
  * Exchange type use cases
  * Publishing to RabbitMQ
  * Consuming from RabbitMQ
  * Filtering messages with direct and topic exchanges
  * Setting up and using authentication
  * Authorizing and blocking application actions
  * Tracking message contents for troubleshooting
---

## Steps to:
- Open a connection:

    `$connection = new AMQPStreamConnection('localhost',5672,"guest","guest");`
- Open a channel:
  
  `$channel = $connection->channel();`
- Declare an exchange:
  
  `$channel->exchange_declare('chat_exchange', AMQPExchangeType::FANOUT,false,true,true);`
- Write message:
  
  `$msg = new AMQPMessage($data);`
- Publish message:
  
  `$channel->basic_publish($msg, 'chat_exchange');`
- Declare a queue:

  `$queue_name = $channel->queue_declare('chat_queue',false,true,false);`

- Bind a queue to an exchange:

  `$channel->queue_bind('chat_queue', 'chat_exchange');`

- Consume messages:

  `$channel->basic_consume('chat_queue','',false,true,false,false,function(AMQPMessage $msg){ echo ' [x] ', $msg->body, "\n";});`

- Keep reading messages:

  ``
