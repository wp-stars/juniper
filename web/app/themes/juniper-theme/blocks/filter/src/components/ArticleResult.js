import React, {useState, useRef, useEffect} from "react";
import Lottie from "lottie-react";
import animation from "../lottie/arrowanimation.json"



const ArticleResult = ({ index, post }) => {

    const lottieRef = useRef();
  
    useEffect(() => {
        lottieRef.current.goToAndStop(0);
      }, []); 
    
      const handleMouseEnter = () => {
        lottieRef.current.setDirection(1);
        lottieRef.current.play();
      };
    
      const handleMouseLeave = () => {
        lottieRef.current.setDirection(-1);
        lottieRef.current.play();
      };
    

    return (
        <div className="container mx-auto mb-12">
            <div className={`w-full article-row grid grid-cols-12 ${index % 2 === 0 ? 'even' : 'odd'}`}>
                <div className="img-content col-span-12 sm:col-span-5">
                    <img src={post.featured_image} />
                </div>
                <div className="article-text flex flex-col items-start text-left justify-center col-span-12 sm:col-span-7">
                    <h3 className="mb-5">{post.post_title}</h3>
                    <p className="mb-10 sm:mb-5 description">{post.post_author} // {post.post_date} // {post.terms.map((term, index) => (
                        <React.Fragment key={index}>
                            {index < post.terms.length && index > 0 && ' // '}
                            <span dangerouslySetInnerHTML={{ __html: `${term.name}` }}></span>
                        </React.Fragment>
                    )
                    )}</p>
                    <div className="mb-10 line-clamp-4">{post.excerpt}</div>
                    <a href={post.link} onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseLeave}>
                        <div className="flex flex-row items-center">
                            <span className="btn-underline">weiter lesen</span>
                            <Lottie
                                lottieRef={lottieRef}
                                animationData={animation}
                                style={{ height: 50, width: 50 }}
                                loop={false}
                                className="lottie-animation"
                            />
                        </div>
                    </a>
                </div>
            </div>
        </div>
    )
}

export default ArticleResult
