namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name:'post_show')]
    public function show(Post $post, Request $request, EntityManagerInterface $em)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->getUser()) {
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }
}
